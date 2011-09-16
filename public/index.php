<?php

// Define base path
use Zend\Debug;
defined('BASE_PATH')
    || define('BASE_PATH', realpath(__DIR__ . '/../'));
    
// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(__DIR__ . '/../application'));

// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/../library'),
    get_include_path(),
)));

// Autoloader
require_once 'Zend/Loader/AutoloaderFactory.php';
Zend\Loader\AutoloaderFactory::factory(array(
    'Zend\Loader\ClassMapAutoloader' => array(
        // ClassMap autoloading for libraries and application
        //BASE_PATH . '/library/Zend/.classmap.php',
        BASE_PATH . '/library/Buggy/.classmap.php',
        BASE_PATH . '/application/.classmap.php',
        BASE_PATH . '/modules/Zf2Module/classmap.php',
        BASE_PATH . '/modules/Zf2Mvc/classmap.php',
    ),
    'Zend\Loader\StandardAutoloader' => array()
));

// Init config
$appConfig = include APPLICATION_PATH . '/configs/application.config.php';

/**
 * Long-hand:
 * $modules = new Zf2Module\ModuleCollection;
 * $modules->getLoader()->registerPaths($appConfig->modulePaths->toArray());
 * $modules->loadModules($appConfig->modules->toArray());
 */
$modules = Zf2Module\ModuleManager::fromConfig($appConfig);

// Get the merged config object
$config = $modules->getMergedConfig();

// Create application, bootstrap, and run
$bootstrap = new $config->bootstrap_class($config);
$application = new Zf2Mvc\Application;
$bootstrap->bootstrap($application);
$application->run()->send();
