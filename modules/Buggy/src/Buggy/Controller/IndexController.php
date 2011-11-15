<?php

namespace Buggy\Controller;

use Zend\Mvc\Router\RouteStack,
    EdpUser\Service\User as UserService,
    Zend\Controller\Action\Helper\FlashMessenger,
    Buggy\Controller\BaseController;

class IndexController extends BaseController
{
    public function indexAction()
    {
        return array();
    }
    
}
