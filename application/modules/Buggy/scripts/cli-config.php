<?php
$classLoader = new \Doctrine\Common\ClassLoader('Entities', __DIR__);
$classLoader->register();

$classLoader = new \Doctrine\Common\ClassLoader('Proxies', __DIR__);
$classLoader->register();

$classLoader = new \Doctrine\Common\ClassLoader('DoctrineExtensions', BASE_PATH . '/library/DoctrineExtensions');
$classLoader->register();

$config = new \Doctrine\ORM\Configuration();
$config->setMetadataCacheImpl(new \Doctrine\Common\Cache\ArrayCache);
$config->setProxyDir(__DIR__ . '/Proxies');
$config->setProxyNamespace('Proxies');

$driverImpl = $config->newDefaultAnnotationDriver(array(
	__DIR__ . '/../src/Buggy/Model', 
	BASE_PATH . '/library/DoctrineExtensions/DoctrineExtensions/Versionable/Entity'
));
$config->setMetadataDriverImpl($driverImpl);

$connectionOptions = array(
    'driver' => 'pdo_mysql',
    'dbname' => 'buggy',
    'user' => 'root'
);

$em = \Doctrine\ORM\EntityManager::create($connectionOptions, $config);

$helperSet = new \Symfony\Component\Console\Helper\HelperSet(array(
    'db' => new \Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper($em->getConnection()),
    'em' => new \Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper($em)
));
//$cli->setHelperSet($helperSet);