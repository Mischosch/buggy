<?php

/** @namespace */
namespace Admin\Controller;

use Buggy\Resource\DoctrineEntityManager, 
	Zf2Mvc\Controller\ActionController;

class IndexController extends ActionController
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
    
	public function setDoctrineEntityManager(DoctrineEntityManager $em)
    {
        $this->em = $em->getEntityManager();
        return $this;
    }


}

