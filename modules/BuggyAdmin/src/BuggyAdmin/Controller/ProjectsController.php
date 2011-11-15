<?php

namespace BuggyAdmin\Controller;

use Buggy\Model\Project,
	Zend\Debug, 
	Zend\Mvc\Controller\ActionController,
	SpiffyDoctrine\Service\Doctrine as EntityManager;
	
class ProjectsController extends ActionController
{

	/**
	 * @var EntityManager
	 */
	protected $em;
	
	protected $userService;
    protected $authService;
	
    public function indexAction()
    {
		/*if (!$this->getAuthService()->hasIdentity()) {
            return $this->redirect()->toRoute('default', array(
                'controller' => 'user',
                'action'     => 'login',
            )); 
        }*/

    	$projects = $this->em->getRepository('Buggy\Model\Project')
    		->getProjectList();
   	    return array('projects' => $projects, 'title' => 'Projects', 'user' => $this->getAuthService()->getIdentity());
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
    
	public function setDoctrineEntityManager(EntityManager $em)
    {
        $this->em = $em->getEntityManager();
        return $this;
    }

    public function getUserService()
    {
        if (null === $this->userService) {
            $this->userService = $this->getLocator()->get('edpuser_user_service');
        }
        return $this->userService;
    }

    public function getAuthService()
    {
        if (null === $this->authService) {
            $this->authService = $this->getUserService()->getAuthService();
        }
        return $this->authService;
    }

}

