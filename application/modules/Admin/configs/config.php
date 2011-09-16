<?php

$config = array(
    'di' => array( 'instance' => array(
        'alias' => array(
            'overview' => 'Admin\Controller\OverviewController',
            'projects' => 'Admin\Controller\ProjectsController',
            'view'  => 'Zend\View\PhpRenderer',
        ),
        
        'Admin\Controller\OverviewController' => array(
        	'parameters' => array(
        		'em' => 'Buggy\Resource\DoctrineEntityManager'
        	)
        ), 
        
        'Admin\Controller\ProjectsController' => array(
        	'parameters' => array(
        		'em' => 'Buggy\Resource\DoctrineEntityManager'
        	)
        ), 

        'Zend\View\PhpRenderer' => array(
        	'methods' => array(
	            'setResolver' => array(
	                'resolver' => 'Zend\View\TemplatePathStack',
	                'options' => array(
	                    'script_paths' => array(
	                        'admin' => __DIR__ . '/../views'
	                    ),
	                ),
	            ),
        	)
        ),
    )),

    'routes' => array(
        'admin' => array(
            'type'    => 'Zf2Mvc\Router\Http\Regex',
            'options' => array(
                'regex' => '/admin/(?P<controller>[^/]+)(/(?P<action>[^/]+)?)?',
                'defaults' => array(
                    'controller' => 'overview',
                    'action'     => 'index',
    				'id' => ''
                ),
                'spec' => '/admin/%s/%s',
            ),
        ),
    ),
);
if (file_exists(__DIR__ . '/config.' . APPLICATION_ENV . '.php')) {
    $config = new Zend\Config\Config($config, true);
    $config->merge(new Zend\Config\Config(include __DIR__ . '/config.' . APPLICATION_ENV . '.php'));
    return $config->toArray();
}
return $config;