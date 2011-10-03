<?php

namespace Buggy\Controller;

use Zend\Mvc\LocatorAware;

use Zend\Mvc\Controller\ActionController,
    Zend\Di\Locator,
	Buggy\Resource\DoctrineEntityManager as DoctrineEntityManager;

class IndexController extends ActionController implements LocatorAware
{
	protected $em;
	protected $locator;

	public function indexAction()
    {
    	return array('test' => 'IT WORKS!');
    }
    
	public function setDoctrineEntityManager(DoctrineEntityManager $em)
    {
        $this->em = $em;
        return $this;
    }
    
	/**
     * @return the $locator
     */
    public function getLocator()
    {
        return $this->locator;
    }

	/**
     * @param Zend\Di\Locator $locator
     */
    public function setLocator(Locator $locator)
    {
        $this->locator = $locator;
    }

}