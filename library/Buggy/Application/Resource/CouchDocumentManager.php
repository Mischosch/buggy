<?php

namespace Buggy\Application\Resource;

use Zend\Application\Resource\AbstractResource as AbstractResource, 
    Doctrine\ODM\CouchDB\Configuration,
    Zend\Registry;
    
class CouchDocumentManager extends AbstractResource
{
	/**
	 * @var Doctrine\ODM\CouchDB/DocumentManager
	 */
	protected $documentManager;
	
	/**
	 * @var array
	 */
	protected $_options = array();

	public function init()
	{				
		return $this->getCouchDocumentManager();
	}
	
	public function getCouchDocumentManager()
	{
		$options = $this->getOptions();
		$couchConfig = new Configuration();

		$documentDirectories = array($options['documentDir']);
        
		$driverImpl = $couchConfig->newDefaultAnnotationDriver($documentDirectories);

		$couchConfig->setMetadataDriverImpl($driverImpl);
        $couchConfig->setLuceneHandlerName('_fti');

        $httpClient = new \Doctrine\CouchDB\HTTP\SocketClient();
        $dbClient = new \Doctrine\CouchDB\CouchDBClient($httpClient, $options['connection']['dbname']);

        $this->documentManager = new \Doctrine\ODM\CouchDB\DocumentManager($dbClient, $couchConfig);
		Registry::set('dm', $this->documentManager);

		return $this->documentManager;

	}
}