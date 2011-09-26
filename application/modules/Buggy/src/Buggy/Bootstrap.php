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
        $di      = $app->getLocator();
        $view    = $di->get('view');
        
        $url     = $view->plugin('url');
        $url->setRouter($app->getRouter());
        
        $request = $app->getRequest();
        $uri = $request->getUri();
        $layoutPath = 'layouts/buggy.phtml';
    	if (strpos($uri, '/admin') !== false) {
            $layoutPath = 'layouts/admin.phtml';
        }
        $listener = new View\Listener($view, $layoutPath);
        $listener->setDisplayExceptionsFlag($this->config->display_exceptions);
        $listener->setDiContainer($di);
        $app->events()->attachAggregate($listener);
        
    }
}