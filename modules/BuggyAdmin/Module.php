<?php

namespace BuggyAdmin;

use Zend\Debug;

use Zend\Module\Manager,
    Zend\Config\Config,
    Zend\Loader\AutoloaderFactory,
    Zend\Module\Consumer\AutoloaderProvider;

class Module implements AutoloaderProvider
{
    public function init(Manager $moduleManager)
    {
        //Debug::dump($moduleManager);exit;
    }

    public function getAutoloaderConfig()
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

    public static function getConfig($env = null)
    {
        return new Config(include __DIR__ . '/configs/module.config.php');
    }
}