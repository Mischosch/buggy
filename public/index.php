<?php
// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

    // Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(__DIR__ . '/../application'));

// Ensure ZF is on the include path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(__DIR__ . '/../library/ZendFramework/library'),
    realpath(__DIR__ . '/../vendor/doctrine/common/lib'),
    realpath(__DIR__ . '/../vendor/doctrine/dbal/lib'),
    realpath(__DIR__ . '/../vendor/doctrine/orm/lib'),
    get_include_path(),
)));

require_once 'Zend/Loader/AutoloaderFactory.php';
Zend\Loader\AutoloaderFactory::factory(array('Zend\Loader\StandardAutoloader' => array()));

$appConfig = include __DIR__ . '/../configs/application.config.php';

$moduleLoader = new Zend\Loader\ModuleAutoloader($appConfig['module_paths']);
$moduleLoader->register();

$moduleManager = new Zend\Module\Manager($appConfig['modules']);
$listenerOptions = new Zend\Module\Listener\ListenerOptions($appConfig['module_listener_options']);
$moduleManager->setDefaultListenerOptions($listenerOptions);
$moduleManager->getConfigListener()->addConfigGlobPath(dirname(__DIR__) . '/config/autoload/*.config.php');
$moduleManager->loadModules();

// Create application, bootstrap, and run
$bootstrap      = new Zend\Mvc\Bootstrap($moduleManager->getMergedConfig());
$application    = new Zend\Mvc\Application;
$bootstrap->bootstrap($application);
$application->run()->send();
