<?php

namespace Buggy\Controller;

use Zf2Mvc\Controller\ActionController,
	Buggy\Resource\DoctrineEntityManager as DoctrineEntityManager;

class IndexController extends ActionController
{
	protected $em;

    public function indexAction()
    {
    	return array('test' => 'IT WORKS!');
    }
    
	public function setDoctrineEntityManager(DoctrineEntityManager $em)
    {
        $this->em = $em;
        return $this;
    }

}