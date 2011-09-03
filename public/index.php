<?php

// Define base path
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
    /*'Zend\Loader\ClassMapAutoloader' => array(
        __DIR__ . '/../library/.classmap.php',
        __DIR__ . '/../application/.classmap.php',
    ),*/
    'Zend\Loader\StandardAutoloader' => array(
        'fallback_autoloader' => true,
    ),
));

// Create application, bootstrap, and run
$application = new Zend\Application\Application (
    APPLICATION_ENV,
    APPLICATION_PATH . '/configs/application.ini'
);
$application->bootstrap()
            ->run();
