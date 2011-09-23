<?php

namespace Buggy\Controller;

use Zend\Mvc\Controller\ActionController,
	Zend\Controller\Plugin\ErrorHandler as ErrorHandler;

class ErrorController extends ActionController
{

    public function errorAction()
    {
        $error = $this->request->getMetadata('error', false);
        if (!$error) {
            $error = array(
                'type'    => 404,
                'message' => 'Page not found',
            );
        }
        
        switch ($error['type']) {
            case self::ERROR_NO_ROUTE:
            case self::ERROR_NO_CONTROLLER:
            default:
                // 404 error -- controller or action not found
                $this->response->setStatusCode(404);
                break;
        }
        
        return array('message' => $error['message']);
    }

    public function getLog()
    {
        /* @var $bootstrap Zend\Application\Bootstrap */
        $bootstrap = $this->getInvokeArg('bootstrap');
        if (!$bootstrap->getBroker()->hasPlugin('Log')) {
            return false;
        }
        $log = $bootstrap->getResource('Log');
        return $log;
    }


}

