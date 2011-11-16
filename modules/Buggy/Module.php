<?php

namespace Buggy;

use Buggy\View\Listener;

use Zend\Debug;

use Zend\Module\Manager,
    Zend\EventManager\StaticEventManager,
    Zend\EventManager\Event,
    Zend\Loader\AutoloaderFactory;

class Module
{
    protected $view;
    protected $viewListener;
    protected static $options;

    public function init(Manager $moduleManager)
    {
        $this->initAutoloader();
        $events = StaticEventManager::getInstance();
        $events->attach('bootstrap', 'bootstrap', array($this, 'initializeView'), 100);
        $moduleManager->events()->attach('init.post', array($this, 'postInit'));
    }

    protected function initAutoloader()
    {
        AutoloaderFactory::factory(array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        ));
    }

    public function getConfig($env = null)
    {
        return include __DIR__ . '/configs/module.config.php';
    }
    
    public function postInit($e)
    {
        $config = $e->getTarget()->getMergedConfig();
        static::$options = $config['buggy'];
    }
    
    public static function getOption($option)
    {
        if (!isset(static::$options[$option])) {
            return null;
        }
        return static::$options[$option];
    }
    
    public function initializeView($e)
    {
        $app          = $e->getParam('application');
        $locator      = $app->getLocator();
        $config       = $e->getParam('config');
        $view         = $this->getView($app);
        $viewListener = $this->getViewListener($view, $config);
        $app->events()->attachAggregate($viewListener);
        $events       = StaticEventManager::getInstance();
        
        $viewListener->registerStaticListeners($events, $locator);
    }

    protected function getViewListener($view, $config)
    {
        if ($this->viewListener instanceof Listener) {
            return $this->viewListener;
        }

        $viewListener       = new Listener($view, $config->layout);
        $viewListener->setDisplayExceptionsFlag($config->display_exceptions);

        $this->viewListener = $viewListener;
        return $viewListener;
    }

    protected function getView($app)
    {
        if ($this->view) {
            return $this->view;
        }

        $di     = $app->getLocator();
        $view   = $di->get('view');
        $url    = $view->plugin('url');
        $url->setRouter($app->getRouter());

        $this->view = $view;
        return $view;
    }
}
