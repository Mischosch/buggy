<?php
$config = array(
    'di' => array( 'instance' => array(
        'alias' => array(
            'admin'       => 'BuggyAdmin\Controller\IndexController',
            'projects'    => 'BuggyAdmin\Controller\ProjectsController',
            'view'        => 'Zend\View\PhpRenderer',
        ),
        
        'BuggyAdmin\Controller\IndexController' => array(
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
                        'buggyadmin' => __DIR__ . '/../views',
                    ),
                ),
            ),
        ),
    )),
    'routes' => array(
        'admin' => array(
            'type' => 'Zend\Mvc\Router\Http\Literal',
            'options' => array(
                'route'    => '/admin',
                'defaults' => array(
                    'namespace'  => 'BuggyAdmin',
                    'controller' => 'admin',
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