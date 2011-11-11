<?php

namespace Buggy;

use Zend\Module\Manager,
    Zend\Loader\AutoloaderFactory;

class Module
{
    protected static $options;

    public function init(Manager $moduleManager)
    {
        $this->initAutoloader();
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
}