<?php

/** @namespace */
namespace Admin\Controller;

use Buggy\Model\Project,
	Zend\Debug, 
	Buggy\Resource\DoctrineEntityManager, 
	Zend\Mvc\Controller\ActionController;

class OverviewController extends ActionController
{

	/**
	 * @var Doctrine\ORM\EntityManager
	 */
	protected $em;
	
    /*public function init()
    {
    	$this->broker('baseinit');
    }*/

    public function indexAction()
    {
    	return array('content' => 'Admin Overview');
    }
    
	public function allAction()
    {
    	$projects = $this->em->getRepository('Buggy\Model\Project')
    		->findAll();
    	Debug::dump($projects);
    	exit;
    }
    
	public function testAction()
    {
    	Debug::dump($this->em->getRepository('Buggy\Model\Project')
    		->findActiveProjectsByTitle('Hallo Welt'));
    	exit;
    }
    
	public function addAction()
    {
    	$projectRecord = new Project();
    	$projectRecord->setTitle('Hallo Welt');
    	$projectRecord->setDescription('do the boogie woogie!');
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
    
	public function setDoctrineEntityManager(DoctrineEntityManager $em)
    {
        $this->em = $em->getEntityManager();
        return $this;
    }


}

