<?php

namespace Buggy;

use Zend\Debug;

use Zend\Config\Config,
    Zend\Di\Configuration,
    Zend\Di\Definition,
    Zend\Di\Definition\Builder,
    Zend\Di\DependencyInjector,
    Zend\EventManager\StaticEventManager,
    Zend\Stdlib\ResponseDescription as Response,
    Zend\View\Variables as ViewVariables,
    Zend\Mvc\Application;

class Bootstrap
{
    protected $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    public function bootstrap(Application $app)
    {
        $this->setupLocator($app);
        $this->setupRoutes($app);
        $this->setupEvents($app);
    }

    protected function setupLocator(Application $app)
    {
        /**
         * Instantiate and configure a DependencyInjector instance, or 
         * a ServiceLocator, and return it.
         */
        $definition = new Definition\AggregateDefinition;
        $definition->addDefinition(new Definition\RuntimeDefinition);

        $di = new DependencyInjector;
        $di->setDefinition($definition);

        $config = new Configuration($this->config->di);
        $config->configure($di);

        $app->setLocator($di);
    }

    protected function setupRoutes(Application $app)
    {
        /**
         * Pull the routing table from configuration, and pass it to the
         * router composed in the Application instance.
         */
        $router = $app->getLocator()->get('Zend\Mvc\Router\SimpleRouteStack');
        foreach ($this->config->routes as $name => $config) {
            $class   = $config->type;
            $options = $config->options;
            $route   = new $class($options);
            $router->addRoute($name, $route);
        }
        $app->setRouter($router);
    }

    protected function setupEvents(Application $app)
    {
        /**
         * Wire events into the Application's EventManager, and/or setup
         * static listeners for events that may be invoked.
         */
        $di      = $app->getLocator();
        $view    = $di->get('view');
        
        $layout  = $di->get('layout');
        $layout->setView($view);
        
        $url     = $view->plugin('url');
        $url->setRouter($app->getRouter());
        
        $request = $app->getRequest();
		
        // Layout
        $layoutHandler = function($content, $response) use ($layout, $view, $request) {
            //$vars       = new ViewVariables(array('content' => $content));
            //$layout     = $view->render('layouts/buggy.phtml', $vars);
            $view->vars()->content = $content;
            $uri = $request->getUri();
            if (strpos($uri, '/admin') !== false) {
            	$layout->setLayout('admin');
            }
            $layoutContent = $layout->render();
            $response->setContent($layoutContent);
        };

        $events = StaticEventManager::getInstance();
        
        // Base Init
        $baseInit = $di->get('BaseInit');
        $events->attach('Zend\Mvc\Controller\ActionController', 'dispatch', function($e) use ($baseInit) {
        	$request = $e->getRequest();
        	$uri = $request->getUri();
        	if (strpos($uri, '/admin') !== false) {
        		$routeMatch = $e->getRouteMatch();
            	$controller = $routeMatch->getParam('controller', 'error');
            	$baseInit->init()->adminInit($controller);
            } else {
            	$baseInit->init()->buggyInit();
            }
        }, 0);
        
        // View Rendering
        $events->attach('Zend\Mvc\Controller\ActionController', 'dispatch', function($e) use ($view, $layoutHandler) {
            $vars = $e->getResult();
            if ($vars instanceof Response) {
                return;
            }

            $response   = $e->getResponse();
            if ($response->getStatusCode() == 404) {
                // Render 404 responses differently
                return;
            }

            $request    = $e->getRequest();
            $routeMatch = $e->getRouteMatch();
            $module     = $routeMatch->getParam('module', null);
            $controller = $routeMatch->getParam('controller', 'error');
            $action     = $routeMatch->getParam('action', 'error');
            $script     = $controller . '/' . $action . '.phtml';
            if (is_object($vars)) {
                if ($vars instanceof Traversable) {
                    $viewVars = new ViewVariables(array());
                    $vars = iterator_apply($vars, function($it) use ($viewVars) {
                        $viewVars[$it->key()] = $it->current();
                    }, $it);
                    $vars = $viewVars;
                } else {
                    $vars = new ViewVariables((array) $vars);
                }
            } else {
                $vars = new ViewVariables($vars);
            }
            
            // Action content
            if (!empty($module)) {
            	$paths = $view->resolver()->getPaths();
            	$pathToSet = false;
            	foreach ($paths AS $path) {
            		if (strpos($path, ucfirst($module))) {
            			$pathToSet = $path;
            		}
            	}
            	if (isset($pathToSet)) {
            		$view->resolver()->setPaths(array($pathToSet));
            	}
            }
            $content = $view->render($script, $vars);

            // Layout
            $layoutHandler($content, $response);
            return $response;
        }, -10);

        // Render 404 pages
        /*$events->attach('Zend\Mvc\Controller\ActionController', 'dispatch', function($e) use ($view, $layoutHandler) {
            $vars = $e->getResult();
            if ($vars instanceof Response) {
                return;
            }

            $response = $e->getResponse();
            if ($response->getStatusCode() != 404) {
                // Only handle 404's
                return;
            }

            $vars = array('message' => 'Page not found.');

            $content = $view->render('error/error.phtml', $vars);

            // Layout
            $layoutHandler($content, $response);
            return $response;
        }, -5);*/

        // Error handling
        $app->events()->attach('dispatch', function($e) use ($view, $layoutHandler) {
            $error   = $e->getResponse()->getStatusCode();
            $app     = $e->getTarget();
            switch ($error) {
                case Application::ERROR_CONTROLLER_NOT_FOUND:
                    $vars = array(
                        'message' => 'Page not found.',
                    );
                    break;
                case Application::ERROR_CONTROLLER_INVALID:
                default:
                    $vars = array(
                        'message' => 'Unable to serve page; invalid controller.',
                    );
                    break;
            }

            $content = $view->render('error/error.phtml', $vars);

            // Layout
            $response = $app->getResponse();
            $layoutHandler($content, $response);
            return $response;
        }, -5);
        
    }
}