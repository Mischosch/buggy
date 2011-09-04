<?php

namespace Buggy\Application\Resource;

use Zend\Application\Resource, 
	Zend\Application\Resource\AbstractResource as AbstractResource,
	Zend\Config\Ini,
	Zend\Registry;

class BuggyModules extends AbstractResource implements Resource
{
    /**
     * @var array
     */
    protected $modules;
    
    /**
     * @var Zend\Cache\Frontend\Core
     */
    protected $cache;
    
    public function init ()
    {
        return $this->getBuggyModules();
    }
    public function getBuggyModules ()
    {
    	$application = $this->getBootstrap()->getApplication();
    	$application->bootstrap('cachemanager');
    	$this->cache = $application->getResource('cachemanager')->getCache('general');
    	if ($this->cache->test('BuggyModules')) {
    		$this->modules = $this->cache->load('BuggyModules');
    	} else {
    		if (!is_file(APPLICATION_PATH . '/configs/modules.ini')) {
    			throw new RuntimeException('modules.ini is missing!');
    		}
    		$config = new Ini(APPLICATION_PATH . '/configs/modules.ini');
    		$this->modules = $config->modules->toArray();
			$this->cache->save($this->modules, 'BuggyModules');
    	}
    	Registry::set('BuggyModules', $this->modules);
        return $this->modules;
    }
}