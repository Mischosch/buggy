<?php

$config = array(
    'di' => array( 'instance' => array(
        'alias' => array(
            'admin_index' => 'Admin\Controller\IndexController',
            'admin_overview' => 'Admin\Controller\OverviewController',
            'admin_projects' => 'Admin\Controller\ProjectsController',
            'view'  => 'Zend\View\PhpRenderer',
        ),
        
        'Admin\Controller\OverviewController' => array(
        	'parameters' => array(
        		'em' => 'Buggy\Resource\DoctrineEntityManager'
        	)
        ), 
        
        'Admin\Controller\ProjectsController' => array(
        	'parameters' => array(
        		'em' => 'Buggy\Resource\DoctrineEntityManager', 
        		'projectService' => 'Admin\Service\Projects', 
        		'router' => 'Zf2Mvc\Router\SimpleRouteStack'
        	)
        ), 
        
        'Admin\Service\Projects' => array(
        	'parameters' => array(
        		'projectForm' => 'Admin\Form\Project'
        	)
        ),

        'Admin\Form\Project' => array(
        	'parameters' => array(
        		'view' => 'Zend\View\PhpRenderer'
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
            'type'    => 'Zf2Mvc\Router\Http\Module',
            'options' => array(
                'route' => '/:module/:controller/:action/*',
                'defaults' => array(
    				'module'     => 'admin',
                    'controller' => 'overview',
                    'action'     => 'index',
                ),
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