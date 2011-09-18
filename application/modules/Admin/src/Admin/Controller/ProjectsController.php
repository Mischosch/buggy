<?php

namespace Admin\Controller;

use Application\Model\Project,
	Admin\Service\Projects, 
	Zend\Filter\Digits, 
	Zend\Controller\Action\Helper\FlashMessenger,
	Zend\Debug, 
	Buggy\Resource\DoctrineEntityManager as DoctrineEntityManager, 
	Zf2Mvc\Controller\ActionController, 
	Zf2Mvc\Router\RouteStack;

class ProjectsController extends ActionController
{

	/**
	 * @var Buggy\Resource\DoctrineEntityManager
	 */
	protected $em;
	
	/**
	 * @var Admin\Service\Projects
	 */
	protected $projectService;
	
	/**
	 * @var Zend\Controller\Action\Helper\FlashMessenger
	 */
	protected $flashMessenger;
	

    public function indexAction()
    {
    	$projects = $this->em->getRepository('Application\Model\Project')
    		->getProjectList(); //->findAll();
   	    return array(
   	    	'projects' => $projects, 
   	    	'title'    => 'Projects',
   	    	'messages' => $this->getFlashMessenger('projects')->getMessages()
   	    );
    }
    
	public function addAction()
    {
    	$form = $this->projectService->getProjectForm();
    	$messages = false;
    	if ($this->getRequest()->isPost()) {
    		if ($this->getRequest()->post()->offsetExists('cancel')) {
    			return $this->redirect('projects', 'index');
    		}
    		if ($form->isValid($this->getRequest()->post()->toArray())) {
    			$values = $form->getValues();
    			$projectRecord = new Project();
    			$projectRecord->setTitle($values['title']);
    			$projectRecord->setDescription($values['description']);
    			$projectRecord->setPublic($values['public']);
		    	$this->em->persist($projectRecord);
		    	$this->em->flush();
		    	$this->getFlashMessenger('projects')->addMessage(array('success', 'Project: <em>'.$projectRecord->getTitle().'</em> added succesfully!'));
    			return $this->redirect('projects', 'index');
    		} else {
    			$messages = array('error', 'Please controll your input!');
    			$form->buildBootstrapErrorDecorators();
    		}
    	}
    		
	    return array(
	    	'title'    => 'Add Project',
	    	'form'     => $form,
    		'messages' => $messages
    	);
    	
    }
    
	public function editAction()
    {
    	$filter = new Digits();
    	$projectId = $filter->filter($this->getQueryParam('id'));
    	if (is_numeric($projectId)) {
	    	$projectRecord = $this->em->find('Application\Model\Project', $projectId);
	    	if ($projectRecord) {
	    		$form = $this->projectService->getProjectForm();
	    		$form->setAction('/admin/projects/edit/id/'.$projectId);
	    		$form->setValues($projectRecord);
	    		$messages = false;
	    		if ($this->getRequest()->isPost()) {
	    			if ($this->getRequest()->post()->offsetExists('cancel')) {
	    				return $this->redirect('projects', 'index');
	    			}
	    			if ($form->isValid($this->getRequest()->post()->toArray())) {
	    				$values = $form->getValues();
	    				$projectRecord->setTitle($values['title']);
	    				$projectRecord->setDescription($values['description']);
	    				$projectRecord->setPublic($values['public']);
				    	$this->em->persist($projectRecord);
				    	$this->em->flush();
				    	$this->getFlashMessenger('projects')->addMessage(array('success', 'Project: <em>'.$projectRecord->getTitle().'</em> saved succesfully!'));
	    				return $this->redirect('projects', 'index');
	    			} else {
	    				$messages = array('error', 'Please controll your input!');
	    				$form->buildBootstrapErrorDecorators();
	    			}
	    		}
	    		
		    	return array(
		    		'title'    => 'Edit Project',
		    		'form'     => $form,
		    		'messages' => $messages
		    	);
	    	}
    	}
    	return $this->redirect('projects', 'index');
    }
    
	public function testAction()
    {
    	Debug::dump($this->em->getRepository('Application\Model\Project')
    		->findActiveProjectsByTitle('Hallo Welt'));
    	exit;
    }
    
    
    /**
     * Redirect to a controller/action combo by using admin route
     * 
     * @param  string $controller
     * @param  string $action
     * @return Zend\Http\Response
     */
	protected function redirect($controller, $action)
    {
        $redirect = $this->router->assemble(
            array('controller' => $controller, 'action' => $action, 'id' => null), 
            array('name' => 'admin', 'reset' => true)
        );
        $this->response->setStatusCode(302);
        $this->response->headers()->addHeaderLine('Location', $redirect);
        return $this->response;
    }
    
    /**
     * Get Flash Messenger Instance
     * 
     * @param  string $namespace
     * @return Zend\Controller\Action\Helper\FlashMessenger
     */
	public function getFlashMessenger($namespace = false)
    {
        if (!$this->flashMessenger) {
            $this->flashMessenger = new FlashMessenger();
        }
        if ($namespace) {
            $this->flashMessenger->setNamespace($namespace);
        }
        return $this->flashMessenger;
    }
    
    /**
     * @param Buggy\Resource\DoctrineEntityManager $em
     * @return Admin\Controller\ProjectsController
     */
	public function setDoctrineEntityManager(DoctrineEntityManager $em)
    {
        $this->em = $em->getEntityManager();
        return $this;
    }
    
    /**
     * @param Admin\Service\Projects $projectService
     * @return Admin\Controller\ProjectsController
     */
	public function setProjectsService(Projects $projectService)
    {
        $this->projectService = $projectService;
        return $this;
    }
    
    /**
     * @param Zf2Mvc\Router\RouteStack $router
     * @return Admin\Controller\ProjectsController
     */
	public function setRouter(RouteStack $router)
    {
        $this->router = $router;
        return $this;
    }

}

