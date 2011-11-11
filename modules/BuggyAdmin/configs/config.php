<?php

$config = array(
	//'bootstrap_class' => 'Application\Bootstrap',
	'layout'          => 'layouts/buggy.phtml',
    'di' => array( 'instance' => array(
        'alias' => array(
            'admin' => 'BuggyAdmin\Controller\IndexController',
            'overview' => 'BuggyAdmin\Controller\OverviewController',
            'projects' => 'BuggyAdmin\Controller\ProjectsController',
            'view'  => 'Zend\View\PhpRenderer',
            'doctrine_em'           => 'SpiffyDoctrine\Factory\EntityManager',

        ),
        
        'BuggyAdmin\Controller\OverviewController' => array(
        	'parameters' => array(
        		'em' => 'doctrine_em'
        	)
        ), 
        
        'BuggyAdmin\Controller\ProjectsController' => array(
        	'parameters' => array(
        		'em' => 'doctrine_em'
        	)
        ), 

        'Zend\View\PhpRenderer' => array(
                'parameters' => array(
                    'resolver' => 'Zend\View\TemplatePathStack',
                    'options'  => array(
                        'script_paths' => array(
                            'application' => __DIR__ . '/../views',
                        ),
                    ),
                ),
            ),
    ))
);
if (file_exists(__DIR__ . '/config.' . APPLICATION_ENV . '.php')) {
    $config = new Zend\Config\Config($config, true);
    $config->merge(new Zend\Config\Config(include __DIR__ . '/config.' . APPLICATION_ENV . '.php'));
    return $config->toArray();
}
return $config;