<?php

namespace Buggy\Controller\Action\Helper;

use Zend\Debug;

use Zend\Controller\Action\Helper\AbstractHelper;

class BaseInit extends AbstractHelper
{
    public function __construct()
    {
        $module = $this->getRequest()->getModuleName();
        switch ($module) {
            case 'admin':
                $this->adminInit();
                break;
            case 'buggy':
                $this->buggyInit();
                break;
        }
    }
    
    /**
     * Admin Init - set JS / CSS
     */
    protected function adminInit() 
    {
    	$view = $this->getFrontController()->getParam('bootstrap')->getResource('view');
    	$view->broker('headScript')->appendFile('/js/admin.js');
        $view->broker('headLink')->appendStylesheet('/css/admin.css', 'screen, projection');
        $view->broker('headTitle')->prepend('Admin');
    }
    
	protected function buggyInit() 
    {
    	$view = $this->getFrontController()->getParam('bootstrap')->getResource('view');
    	$view->broker('headScript')->appendFile('buggy.js');
        $view->broker('headLink')->appendStylesheet('/css/buggy.css', 'screen, projection');
    }
    
}