<?php

namespace Buggy\View;

use ArrayAccess,
    Zend\EventManager\EventCollection,
    Zend\EventManager\StaticEventCollection,
    Zend\EventManager\ListenerAggregate,
    Zend\Http\Response,
    Zend\Mvc\Application,
    Zend\Mvc\MvcEvent,
    Zend\View\Renderer;

class Listener implements ListenerAggregate
{
    protected $layout;
    protected $listeners = array();
    protected $staticListeners = array();
    protected $view;
    protected $di;
    protected $displayExceptions = false;
    protected $defaultNamespace = 'buggy';

    public function __construct(Renderer $renderer, $layout = 'layout.phtml')
    {
        $this->view   = $renderer;
        $this->layout = $layout;
    }

    public function setDisplayExceptionsFlag($flag)
    {
        $this->displayExceptions = (bool) $flag;
        return $this;
    }

    public function displayExceptions()
    {
        return $this->displayExceptions;
    }
    
	public function setDiContainer($di)
    {
        $this->di = $di;
        return $this;
    }

    public function attach(EventCollection $events)
    {
        $this->listeners[] = $events->attach('dispatch.error', array($this, 'baseInit'), 1);
        $this->listeners[] = $events->attach('dispatch.error', array($this, 'renderError'), 0);
        $this->listeners[] = $events->attach('dispatch', array($this, 'render404'), -80);
        $this->listeners[] = $events->attach('dispatch', array($this, 'renderLayout'), -1000);
    }

    public function detach(EventCollection $events)
    {
        foreach ($this->listeners as $key => $listener) {
            $events->detach($listener);
            unset($this->listeners[$key]);
            unset($listener);
        }
    }
    
    public function registerStaticListeners(StaticEventCollection $events, $locator)
    {
        $ident   = 'Application\Controller\PageController';
        $handler = $events->attach($ident, 'dispatch', array($this, 'renderPageController'), -50);
        $this->staticListeners[] = array($ident, $handler);

        $ident   = 'Zend\Mvc\Controller\ActionController';
        $handler = $events->attach($ident, 'dispatch', array($this, 'renderView'), -50);
        $this->staticListeners[] = array($ident, $handler);
        
        $ident   = 'Zend\Mvc\Controller\ActionController';
        $handler = $events->attach($ident, 'dispatch', array($this, 'baseInit'), 50);
        $this->staticListeners[] = array($ident, $handler);
    }
    
    public function baseInit(MvcEvent $e)
    {
    	$baseInit = $this->di->get('BaseInit');
    	$request = $e->getRequest();
        $uri = $request->getUri();
        $routeMatch = $e->getRouteMatch();
        $namespace = $routeMatch->getParam('namespace', $this->defaultNamespace);
        $controller = $routeMatch->getParam('controller', 'error');
        $init = $namespace . 'Init';
    	$baseInit->init()->$init($controller);
    }

    public function renderView(MvcEvent $e)
    {
        $response = $e->getResponse();
        if (!$response->isSuccess()) {
            return;
        }
        
        $routeMatch = $e->getRouteMatch();
        $namespace  = $routeMatch->getParam('namespace', $this->defaultNamespace);
        $controller = $routeMatch->getParam('controller', 'index');
        $action     = $routeMatch->getParam('action', 'index');
        $script     = $controller . '/' . $action . '.phtml';

        $vars       = $e->getResult();
        if (is_scalar($vars)) {
            $vars = array('content' => $vars);
        } elseif (is_object($vars) && !$vars instanceof ArrayAccess) {
            $vars = (array) $vars;
        }

        // Action content
        $paths = $this->view->resolver()->getPaths();
        $pathToSet = false;
        foreach ($paths as $path) {
            if (strpos($path, ucfirst($namespace))) {
                $pathToSet = $path;
            }
        }
        if (isset($pathToSet)) {
            $this->view->resolver()->setPaths(array($pathToSet));
        }
        $content = $this->view->render($script, $vars);

        $e->setResult($content);
        return $content;
    }

    public function renderLayout(MvcEvent $e)
    {
        $response = $e->getResponse();
        if (!$response) {
            $response = new Response();
            $e->setResponse($response);
        }
        if ($response->isRedirect()) {
            return $response;
        }
        
        $vars = array('controller' => $this->view->vars('controller'));
        
        if (false !== ($contentParam = $e->getParam('content', false))) {
            $vars['content'] = $contentParam;
        } else {
            $vars['content'] = $e->getResult();
        }
        
        $namespace  = $e->getRouteMatch()
            ->getParam('namespace', $this->defaultNamespace);
        $this->layout = 'layouts/' . $namespace . '.phtml';
        
        $layout = $this->view->render($this->layout, $vars);
        $response->setContent($layout);
        return $response;
    }

    public function render404(MvcEvent $e)
    {
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

        $content = $this->view->render('error/index.phtml', $vars);

        $e->setResult($content);

        return $this->renderLayout($e);
    }

    public function renderError(MvcEvent $e)
    {
        $error    = $e->getError();
        $app      = $e->getTarget();
        $response = $e->getResponse();
        if (!$response) {
            $response = new Response();
            $e->setResponse($response);
        }

        switch ($error) {
            case Application::ERROR_CONTROLLER_NOT_FOUND:
            case Application::ERROR_CONTROLLER_INVALID:
                $vars = array(
                    'message' => 'Page not found.',
                );
                $response->setStatusCode(404);
                break;

            case Application::ERROR_EXCEPTION:
            default:
                $exception = $e->getParam('exception');
                $vars = array(
                    'message'            => 'An error occurred during execution; please try again later.',
                    'exception'          => $e->getParam('exception'),
                    'display_exceptions' => $this->displayExceptions(),
                );
                $response->setStatusCode(500);
                break;
        }

        $content = $this->view->render('error/index.phtml', $vars);

        $e->setResult($content);

        return $this->renderLayout($e);
    }
}
