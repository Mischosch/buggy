<?php

/** @namespace */
namespace Admin;

use Application\Model\Project,
	Zend\Registry,
	Zend\Debug, 
	Zend\Controller\Action as ActionController;

class ProjectsController extends ActionController
{

	/**
	 * @var Doctrine\ORM\EntityManager
	 */
	protected $em;
	
    public function init()
    {
    	$this->broker('baseinit');
    	$this->em = Registry::get('em');
    }

    public function indexAction()
    {
    	$projects = $this->em->getRepository('Application\Model\Project')
    		->getProjectList();
   	    $this->view->vars()->projects = $projects;
    }
    
	public function allAction()
    {
    	$projects = $this->em->getRepository('Application\Model\Project')
    		->findAll();
    	Debug::dump($projects);
    	exit;
    }
    
	public function testAction()
    {
    	Debug::dump($this->em->getRepository('Application\Model\Project')
    		->findActiveProjectsByTitle('Hallo Welt'));
    	exit;
    }
    
	public function addAction()
    {
    	$projectRecord = new Project();
    	$projectRecord->setTitle('Hallo Welt');
    	$projectRecord->setDescription('do the boogie woogie!');
    	$projectRecord->setPublic(1);
    	$this->em->persist($projectRecord);
    	$this->em->flush();
    	Debug::dump($projectRecord);
    	exit;
    }
    
	public function updateAction()
    {
    	$projectRecord = $project = $this->em->find('Application\Model\Project', 1);
    	if ($projectRecord) {
	    	$projectRecord->setTitle('Hallo Welt 123');
	    	$this->em->persist($projectRecord);
	    	$this->em->flush();
    	}
    	Debug::dump($projectRecord);
    	exit;
    }


}

