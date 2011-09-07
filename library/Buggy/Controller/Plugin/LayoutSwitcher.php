<?php

namespace Buggy\Controller\Plugin;

use Zend\Controller\Plugin\AbstractPlugin, 
	Zend\Layout\Layout, 
	Zend\Controller\Request as Request;

class LayoutSwitcher extends AbstractPlugin
{
	public function dispatchLoopStartup(Request\AbstractRequest $request)
    {
        if ('buggy' == $request->getModuleName()) {
            return;
        }
        Layout::getMvcInstance()->setLayout($request->getModuleName());
    }
}