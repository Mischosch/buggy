<?php

namespace Buggy\Resource;

use Zend\Debug;

use Zend\Application\Resource,
	Zend\Application\Resource\AbstractResource as AbstractResource, 
	Doctrine\ORM\EntityManager,
    Doctrine\ORM\Configuration, 
    Doctrine\Common\ClassLoader, 
    Zend\Registry, 
    Doctrine\Common\EventManager, 
    Gedmo\Timestampable\TimestampableListener,
    Gedmo\Sluggable\SluggableListener, 
    Gedmo\Tree\TreeListener, 
    DoctrineExtensions\Versionable\VersionListener;

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
	
	/**
     * Create a instance with options
     *
     * @param mixed $options
     */
    public function __construct($options = null)
    {
        if (is_array($options)) {
            $this->setOptions($options);
        } else if ($options instanceof \Zend\Config\Config) {
            $this->setOptions($options->toArray());
        }
        $this->init();
    }

	public function init()
	{
		return $this->setupDoctrineEntityManager();
	}
	
	public function setupDoctrineEntityManager()
	{	
		$classLoader = new ClassLoader('Gedmo', BASE_PATH . '/library/DoctrineExtensions');
		$classLoader->register();
		
		$classLoader = new ClassLoader('DoctrineExtensions', BASE_PATH . '/library/DoctrineExtensions');
		$classLoader->register();
		
		$evm = new EventManager();
		// timestampable
		$evm->addEventSubscriber(new TimestampableListener());
		// sluggable
		$evm->addEventSubscriber(new SluggableListener());
		// tree
		//$evm->addEventSubscriber(new TreeListener());
		// versionable
		$evm->addEventSubscriber(new VersionListener());
		
		$options = $this->getOptions();
		$config = new Configuration;
		$cache = new $options['cacheImplementation'];
		$driverImpl = $config->newDefaultAnnotationDriver(array(
			$options['modelDir'], 
			'DoctrineExtensions\Versionable\Entity\ResourceVersion'
		));
		
		$config->setMetadataDriverImpl($driverImpl);
		$config->setMetadataCacheImpl($cache);
		$config->setQueryCacheImpl($cache);
		$config->setProxyDir($options['proxyDir']);
		$config->setProxyNamespace($options['proxyNamespace']);
		$config->setAutoGenerateProxyClasses($options['autoGenerateProxyClasses']);

		$this->entityManager = EntityManager::create($options['connection'], $config, $evm);
	}
	
	/**
	 * Get EntityManager
	 */
	public function getEntityManager()
	{
		return $this->entityManager;
	}

}