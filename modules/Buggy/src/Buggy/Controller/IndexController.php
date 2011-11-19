<?php

namespace Buggy\Controller;

use Zend\Debug;

use Zend\Mvc\Router\RouteStack,
    EdpUser\Service\User as UserService,
    Zend\Controller\Action\Helper\FlashMessenger,
    Buggy\Controller\BaseController,
    Doctrine\ODM\CouchDB\DocumentManager as DocumentManager,
    SpiffyDoctrine\Factory\EntityManager as EntityManager;

class IndexController extends BaseController
{
	protected $em;
	protected $dm;
    public function indexAction()
    {
    	
    	$projects =  $this->dm->getRepository('Buggy\Document\Article')->findAll();
  
        return array('content' => $projects);
    }

	public function setDocumentManager(DocumentManager $dm)
    {
        $this->dm = $dm;
        return $this;
    }

	public function setDoctrineEntityManager(EntityManager $em)
    {
        $this->em = $em->getEntityManager();
        return $this;
    }
}
