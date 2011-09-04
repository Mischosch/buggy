<?php

namespace Buggy\Application\Resource;

use Zend\Application\Resource\AbstractResource as AbstractResource, 
	Doctrine\ODM\CouchDB,
    Doctrine\ODM\CouchDB\Configuration, 
    Zend\Registry;

class CouchDocumentManager extends AbstractResource
{
	/**
	 * @var Doctrine\ORM\EntityManager
	 */
	protected $documentManager;
	
	/**
	 * @var array
	 */
	protected $_options = array(
		'connection' => array(
			'driver' => 'pdo_mysql', 
			'host' => 'localhost', 
			'dbname' => 'dbname', 
			'user' => 'root', 
			'password' => ''
		),
		'cacheImplementation' => '\Doctrine\Common\Cache\ArrayCache', 
		'modelDir' => '/models',
		'proxyDir' => '/proxies',
		'proxyNamespace' => 'Proxies',
		'autoGenerateProxyClasses' => true
	);

	public function init()
	{
		$options = $this->getOptions();
		$config = new Configuration;
		$cache = new $options['cacheImplementation'];
		$driverImpl = $config->newDefaultAnnotationDriver($options['modelDir']);
		
		$config->setMetadataDriverImpl($driverImpl);
		$config->setMetadataCacheImpl($cache);
		$config->setQueryCacheImpl($cache);
		$config->setProxyDir($options['proxyDir']);
		$config->setProxyNamespace($options['proxyNamespace']);
		$config->setAutoGenerateProxyClasses($options['autoGenerateProxyClasses']);

		$this->entityManager = EntityManager::create($options['connection'], $config);
		Registry::set('em', $this->entityManager);

		return $this->entityManager;
	}
}