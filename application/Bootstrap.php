<?php

use Buggy\Application\Resource\Doctrine, 
	Zend\Application\Bootstrap as BaseBootstrap;
	
class Bootstrap extends BaseBootstrap
{

	/**
	 * Init Doctrine EntityManager
	 * 
	 * @return Doctrine\ORM\EntityManager
	 */
	protected function _initDoctrine()
    {
    	$options = $this->getOption('resources');
    	$doctrine = new Doctrine($options['doctrine']);
    	$doctrine->init();
    	return $doctrine;
    }
    
    /**
	 * Init View Options
	 * 
	 * @return void
	 */
	protected function _initViewOptions()
    {
        $this->bootstrap('view');
        $view = $this->getResource('view');
        $view->broker('doctype')->setDoctype('HTML5');
        $view->broker('headTitle')->setSeparator(' | ')
        	->prepend('Buggy');
        $view->broker('headScript')->appendFile('/js/vendor/jquery.min.js');
        $view->broker('headLink')->appendStylesheet(
        	'http://twitter.github.com/bootstrap/assets/css/bootstrap-1.1.1.min.css'
        );
    }

}

