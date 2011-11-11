<?php

namespace BuggyAdmin\Controller;

use Buggy\Model\Project,
	Zend\Debug, 
	Zend\Mvc\Controller\ActionController,
	SpiffyDoctrine\Factory\EntityManager as EntityManager;
	
class ProjectsController extends ActionController
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
    	$projects = $this->em->getRepository('Buggy\Model\Project')
    		->getProjectList();
   	    return array('projects' => $projects, 'title' => 'Projects');
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
    	$projectRecord->setVersion(1);
    	$projectRecord->setDescription('do the boogie woogie!');
    	$projectRecord->setPublic(1);
    	$this->em->persist($projectRecord);
    	$this->em->flush();
    	Debug::dump($projectRecord);
    	exit;
    }
    
	public function editAction()
    {
    	$projectRecord = $project = $this->em->find('Buggy\Model\Project', 1);
    	if ($projectRecord) {
	    	$projectRecord->setTitle('Hallo Welt 123');
	    	$this->em->persist($projectRecord);
	    	$this->em->flush();
    	}
    	Debug::dump($projectRecord);
    	exit;
    }
    
	public function setDoctrineEntityManager($em)
    {
        $this->em = $em;
        return $this;
    }


}

