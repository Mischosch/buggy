<?php

use Zend\Application\Bootstrap as BaseBootstrap;
	
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
        $view->broker('headScript')->appendFile('/js/vendor/jquery.min.js');
        $view->broker('headLink')->appendStylesheet(
        	'http://twitter.github.com/bootstrap/assets/css/bootstrap-1.1.1.min.css'
        );
    }
    
    /**
	 * Init Resourceloader - add Document ResourceType
	 * 
	 * @return void
	 */
    protected function _initDocumentResourceType()
	{
		$this->getResourceLoader()->addResourceType('document','documents', 'Document');		
	}
	

}

