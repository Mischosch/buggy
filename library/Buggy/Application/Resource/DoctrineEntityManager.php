<?php

namespace Buggy\Application\Resource;

use Zend\Application\Resource,
	Zend\Application\Resource\AbstractResource as AbstractResource, 
	Doctrine\ORM\EntityManager,
    Doctrine\ORM\Configuration, 
    Zend\Registry;

class DoctrineEntityManager extends AbstractResource implements Resource
{
	/**
	 * @var Doctrine\ORM\EntityManager
	 */
	protected $entityManager;
	
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
		return $this->getDoctrineEntityManager();
	}
	
	public function getDoctrineEntityManager()
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