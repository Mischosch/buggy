<?php

namespace Buggy\Action\Helper;

use Zend\Debug;

use Zend\Controller\Action\Helper\AbstractHelper;

class BaseInit extends AbstractHelper
{
	
	/**
	 * @var Zend\View\PhpRenderer
	 */
	protected $view;
    
	public function init() 
	{
        $this->view->plugin('doctype')->setDoctype('HTML5');
        $this->view->plugin('headTitle')->setSeparator(' | ')
        	->prepend('Buggy');
        $this->view->plugin('headScript')->appendFile('/js/jquery.min.js');
        $this->view->plugin('headLink')->appendStylesheet('/css/bootstrap.min.css');
        return $this;
	}
	
    /**
     * Admin Init - set JS / CSS
     * 
     * @param string $controllerName
     */
    public function adminInit($controllerName) 
    {
    	$this->view->plugin('headScript')->appendFile('/js/admin.js');
        $this->view->plugin('headLink')->appendStylesheet('/css/admin.css', 'screen, projection');
        $this->view->plugin('headTitle')->prepend('Admin');
        $this->view->vars()->controller = $controllerName;
        $this->view->plugin('headTitle')->prepend(ucfirst($controllerName));
        return $this;
    }
    
	public function buggyInit($controllerName) 
    {
    	$this->view->plugin('headScript')->appendFile('buggy.js');
        $this->view->plugin('headLink')->appendStylesheet('/css/buggy.css', 'screen, projection');
        $this->view->plugin('headTitle')->prepend(ucfirst($controllerName));
        return $this;
    }
    
    public function setView($view)
    {
    	$this->view = $view;
    	return $this;
    } 
}