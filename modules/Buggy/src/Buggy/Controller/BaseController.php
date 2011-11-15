<?php

namespace Buggy\Controller;

use Zend\Debug;

use Zend\Mvc\Controller\ActionController;

class BaseController extends ActionController
{
    public function init()
    {
        $routeMatch = $this->getEvent()->getParam('route-match');
        $controllerName = 'Error';
        if ($routeMatch) {
            $controllerName = $routeMatch->getParam('controller');
        }
        $this->buggyInit($controllerName);
    }
    
    public function buggyInit($controllerName)
    {
        $view = $this->getLocator()->get('view');
        $view->plugin('doctype')->setDoctype('HTML5');
        $view->plugin('headTitle')->setSeparator(' | ')
            ->prepend('Buggy');
        $view->plugin('headScript')->appendFile('/js/jquery.min.js');
        $view->plugin('headLink')->appendStylesheet('/css/bootstrap.min.css');
        $view->plugin('headScript')->appendFile('buggy.js');
        $view->plugin('headLink')->appendStylesheet('/css/buggy.css', 'screen, projection');
        $view->plugin('headTitle')->prepend(ucfirst($controllerName));
        $view->vars()->controller = $controllerName;
    }
}
