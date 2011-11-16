<?php

namespace BuggyAdmin\Controller;

use Buggy\Model\Project,
	Zend\Registry,
	Zend\Debug, 
	Zend\Mvc\Controller\ActionController,
	Doctrine\ORM\EntityManager as EntityManager;

class IndexController extends BaseController
{

	/**
	 * @var EntityManager
	 */
	protected $em;

    public function indexAction()
    {
        Debug::dump($this->getEvent()->getParam('route-match'));
        exit();
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
    	Debug::dump($this->em->getRepository('Application\Model\Project')
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
    
	public function setDoctrineEntityManager(EntityManager $em)
    {
        $this->em = $em;
        return $this;
    }


}

