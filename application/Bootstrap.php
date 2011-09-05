<?php

use Zend\Application\Bootstrap as BaseBootstrap, 
	Buggy\Controller\Plugin\LayoutSwitcher;
	
class Bootstrap extends BaseBootstrap
{     
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
        $view->broker('headScript')->appendFile('/js/jquery.min.js');
        $view->broker('headLink')->appendStylesheet('/css/bootstrap.min.css');
    }
    
	/**
     * Initializing action helpers
     */
    public function _initPlugins()
    {
        $this->bootstrap('FrontController');
        $fc = $this->getResource('FrontController');
        $fc->registerPlugin(new LayoutSwitcher());
    }
    
	/**
     * Initializing action helpers
     */
    public function _initActionHelpers()
    {
        $this->bootstrap('FrontController');
        $fc = $this->getResource('FrontController');
        $test = $fc->getHelperBroker()->getClassLoader()->registerPlugin('baseinit', 'Buggy\Controller\Action\Helper\BaseInit');
    }

}