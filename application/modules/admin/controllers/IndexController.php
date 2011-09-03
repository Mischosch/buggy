<?php

/** @namespace */
namespace Admin;

use Application\Model\Projects,
	Zend\Registry,
	Zend\Debug, 
	Zend\Controller\Action as ActionController;

class IndexController extends ActionController
{

	/**
	 * @var Doctrine\ORM\EntityManager
	 */
	protected $em;
	
    public function init()
    {
    	$this->em = Registry::get('em');
    }

    public function indexAction()
    {
    	$project = $this->em->find('Application\Model\Projects', 1);
    	Debug::dump($project);
    }
    
	public function allAction()
    {
    	$projects = $this->em->getRepository('Application\Model\Projects')
    		->findAll();
    	Debug::dump($projects);
    	exit;
    }
    
	public function testAction()
    {
    	Debug::dump($this->em->getRepository('Application\Model\Projects')
    		->findActiveProjectsByTitle('Hallo Welt'));
    	exit;
    }
    
	public function addAction()
    {
    	$projectRecord = new Projects();
    	$projectRecord->setTitle('Hallo Welt');
    	$projectRecord->setDescription('do the boogie woogie!');
    	$this->em->persist($projectRecord);
    	$this->em->flush();
    	$this->_redirect('/');
    }


}

