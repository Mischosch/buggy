<?php

// Define path to application directory
defined('BASE_PATH')
    || define('BASE_PATH', realpath(__DIR__ . '/../'));

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(BASE_PATH . '/library'),
    get_include_path(),
)));

require_once 'Doctrine/Common/ClassLoader.php';
$classLoader = new \Doctrine\Common\ClassLoader('Doctrine');
$classLoader->register();
$classLoader = new \Doctrine\Common\ClassLoader('Symfony', 'Doctrine');
$classLoader->register();

require_once getcwd() . "/cli-couch-config.php";

$cli = new \Symfony\Component\Console\Application('Doctrine CouchDB CLI', Doctrine\ODM\CouchDB\Version::VERSION);
$cli->setHelperSet($helperSet);
$cli->addCommands(array(
    new \Doctrine\CouchDB\Tools\Console\Command\ReplicationStartCommand(),
    new \Doctrine\CouchDB\Tools\Console\Command\ReplicationCancelCommand(),
    new \Doctrine\CouchDB\Tools\Console\Command\ViewCleanupCommand(),
    new \Doctrine\CouchDB\Tools\Console\Command\CompactDatabaseCommand(),
    new \Doctrine\CouchDB\Tools\Console\Command\CompactViewCommand(),
    new \Doctrine\CouchDB\Tools\Console\Command\MigrationCommand(),
    new \Doctrine\ODM\CouchDB\Tools\Console\Command\UpdateDesignDocCommand(),
));
$cli->run();
