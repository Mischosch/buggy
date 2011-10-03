<?php

namespace Buggy;

use InvalidArgumentException,
    Zend\Module\Manager,
    Zend\Config\Config,
    Zend\Di\Locator,
    Zend\EventManager\EventCollection,
    Zend\EventManager\StaticEventCollection,
    Zend\Loader\AutoloaderFactory;

class Module
{
    protected $appListeners    = array();
    protected $staticListeners = array();
    protected $viewListener;
    
	public function init()
    {
        $this->initAutoloader();
    }

    protected function initAutoloaderNew()
    {
        include __DIR__ . '/autoload_register.php';
    }

    protected function initAutoloader()
    {
        AutoloaderFactory::factory(array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/classmap.php',
            ),
		    'Zend\Loader\StandardAutoloader' => array(
            	'namespaces' => array(
            		'Doctrine' => BASE_PATH.'/library/Doctrine',
            		'Buggy'    => __DIR__ . '/src/Buggy'
            	),
            	'fallback_autoloader' => false
            )
        ));
    }
	
    public static function getConfig()
    {
        return new Config(include __DIR__ . '/configs/config.php');
    }
    
    public function registerApplicationListeners(EventCollection $events, Locator $locator, Config $config)
    {
        $view          = $locator->get('view');
        $viewListener  = $this->getViewListener($view, $config, $locator);
        $events->attachAggregate($viewListener);
    }

    public function registerStaticListeners(StaticEventCollection $events, Locator $locator, Config $config)
    {
        $view         = $locator->get('view');
        $viewListener = $this->getViewListener($view, $config, $locator);
        $viewListener->registerStaticListeners($events, $locator);
    }

    protected function getViewListener($view, $config, $locator)
    {
        if ($this->viewListener instanceof View\Listener) {
            return $this->viewListener;
        }

        $viewListener       = new View\Listener($view, $config->layout);
        $viewListener->setDisplayExceptionsFlag($config->display_exceptions);
        $viewListener->setDiContainer($locator);
        
        $this->viewListener = $viewListener;
        return $viewListener;
    }
}