<?php

$httpClient = new \Doctrine\CouchDB\HTTP\SocketClient();
$dbClient = new \Doctrine\CouchDB\CouchDBClient($httpClient, 'buggy');

//$dbClient->create(array('dbname' => 'buggy', 'type' => 'socket', 'host' => 'localhost', 'port' => 5984, 'user' => 'admin', 'password' => 'c29X9NmCxjwA2L', 'ip' => '127.0.0.1', 'logging' => true));

$couchConfig = new \Doctrine\ODM\CouchDB\Configuration();

$driverImpl = $couchConfig->newDefaultAnnotationDriver(__DIR__ . '/../application/documents');
$couchConfig->setMetadataDriverImpl($driverImpl);
		
$dm = new \Doctrine\ODM\CouchDB\DocumentManager($dbClient, $couchConfig);

$helperSet = new \Symfony\Component\Console\Helper\HelperSet(array(
				'couchdb' => new \Doctrine\CouchDB\Tools\Console\Helper\CouchDBHelper(null, $dm)
				));
