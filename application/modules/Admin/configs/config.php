<?php
return array_replace_recursive(array(
    'di' => array( 'instance' => array(
        'alias' => array(
            'admin-index'    => 'Admin\Controller\IndexController',
            'admin-overview' => 'Admin\Controller\OverviewController',
            'admin-projects' => 'Admin\Controller\ProjectsController',
            'view'           => 'Zend\View\PhpRenderer',
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
        		'router' => 'Zend\Mvc\Router\SimpleRouteStack'
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
	                        'admin' => __DIR__ . '/../views',
	                    ),
	                ),
	            ),
        	)
        ),
    )),

    'routes' => array(
        'admin' => array(
            'type'    => 'Zend\Mvc\Router\Http\Namespaces',
            'options' => array(
                'namespace' => 'admin', 
                'defaults' => array(
                    'namespace'  => 'admin',
                    'controller' => 'index',
                    'action'     => 'index'
                ),
            ),
        ),
        'projectedit' => array(
            'type'    => 'Zend\Mvc\Router\Http\Route',
            'options' => array(
                'route' => '/admin/pedit/:id',
                'defaults' => array(
                    'namespace'  => 'admin',
                    'controller' => 'projects',
                    'action'     => 'edit',
                    'id'     	 => '',
                ),
            ),
        ),
    ),
), (file_exists(__DIR__ . '/config.' . APPLICATION_ENV . '.php')) ? include __DIR__ . '/config.' . APPLICATION_ENV . '.php' : array());