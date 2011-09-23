<?php

namespace Buggy;

use Zend\Config\Config,
    Zend\Loader\AutoloaderFactory;

class Module
{
	
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
}