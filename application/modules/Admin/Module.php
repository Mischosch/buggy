<?php

namespace Admin;

use Zend\Config\Config,
    Zend\Loader\AutoloaderFactory;

class Module
{
    public static function getConfig()
    {
        return new Config(include __DIR__ . '/configs/config.php');
    }

    public function init($eventCollection)
    {
        $this->initAutoloader();
    }

    protected function initAutoloader()
    {
        AutoloaderFactory::factory(array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/classmap.php',
            ),
		    'Zend\Loader\StandardAutoloader' => array(
            	'namespaces' => array('Admin' => __DIR__  . '/src/Admin'),
            	'fallback_autoloader' => false
            )
        ));
    }
}