<?php

// Define base path
defined('BASE_PATH')
    || define('BASE_PATH', realpath(__DIR__ . '/../'));
    
// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', BASE_PATH .'/application');

// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    BASE_PATH . '/library',
    get_include_path(),
)));

// Autoloader
require_once 'Zend/Loader/AutoloaderFactory.php';
Zend\Loader\AutoloaderFactory::factory(array(
    'Zend\Loader\StandardAutoloader' => array()
));

// Init config
$appConfig = include APPLICATION_PATH . '/configs/application.config.php';

// Module Loader
$moduleLoader = new Zend\Loader\ModuleAutoloader($appConfig->module_paths);
$moduleLoader->register();

// Module Manager
$moduleManager = new Zend\Module\Manager(
    $appConfig->modules,
    new Zend\Module\ManagerOptions($appConfig->module_config)
);

// Get the merged config object
$config = $moduleManager->getMergedConfig();

// Create application, bootstrap, and run
$bootstrap = new $config->bootstrap_class($config, $moduleManager);
$application = new Zend\Mvc\Application;
$bootstrap->bootstrap($application);
$application->run()->send();
