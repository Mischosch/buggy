<?php
return array_replace_recursive(array(
    'bootstrap_class' => 'Buggy\Bootstrap',
    'display_exceptions' => 1,
    'di' => array( 'instance' => array(
        'alias' => array(
            'index'    => 'Buggy\Controller\IndexController',
            'error'    => 'Buggy\Controller\ErrorController',
            'view'     => 'Zend\View\PhpRenderer',
            'layout'   => 'Zend\Layout\Layout',
			'em'       => 'Buggy\Resource\DoctrineEntityManager',
			'dm'       => 'Buggy\Resource\CouchDocumentManager',
			'BaseInit' => 'Buggy\Action\Helper\BaseInit'
        ),

        'Zend\View\HelperLoader' => array('parameters' => array(
            'map' => array(
                'url' => 'Buggy\View\Helper\Url',
                'messagesFormatter' => 'Buggy\View\Helper\MessagesFormatter',
            ),
        )),
        
        'Zend\View\HelperBroker' => array('parameters' => array(
            'loader' => 'Zend\View\HelperLoader',
        )),
        
        'Zend\View\PhpRenderer' => array(
        	'methods' => array(
            	'setResolver' => array(
                	'resolver' => 'Zend\View\TemplatePathStack',
                	'options' => array(
                    	'script_paths' => array(
                        	'buggy' => __DIR__ . '/../views'
                    	),
                	),
            	),
            ),
            'parameters' => array(
                'broker' => 'Zend\View\HelperBroker',
            )
        ),
        
        'Buggy\Action\Helper\BaseInit' => array(
        	'parameters' => array(
        		'view' => 'view'
        	)
        ), 
        
        'Buggy\Resource\DoctrineEntityManager' => array(
        	'parameters' => array(
        		'options' => array(
        			'autoGenerateProxyClasses' => 1, 
        			'cacheImplementation' 	   => '\Doctrine\Common\Cache\ArrayCache',
        			'modelDir' 			  	   => __DIR__ . '/../src/Buggy/Model',
        			'proxyDir' 			  	   => __DIR__ . '/../src/Buggy/Proxies',
        			'connection'   => array(
        				'driver'   => 'pdo_mysql', 
        				'host'     => 'localhost',
        				'dbname'   => 'buggy',
        				'user'     => 'root',
        				'password' => '',
        			)
        		)
        	)
        ),
        
        'Buggy\Resource\CouchDocumentManager' => array(
        	'parameters' => array(
        		'options' => array(
        			'documentDir' => __DIR__ . '/../src/Buggy/Document',
        			'connection'   => array(
        				'host'     => 'localhost',
        				'dbname'   => 'buggy',
        			)
        		)
        	)
        ),
        
        'Buggy\Controller\IndexController' => array(
        	'parameters' => array(
        		'em' => 'Buggy\Resource\DoctrineEntityManager'
        	)
        ), 
    )),

    'routes' => array(
        'default' => array(
            'type'    => 'Zend\Mvc\Router\Http\Regex',
            'options' => array(
                'regex' => '/(?P<controller>[^/]+)(/(?P<action>[^/]+)?)?',
                'defaults' => array(
                    'controller' => 'error',
                    'action'     => 'error',
                ),
                'spec' => '/%s/%s',
            ),
        ),
        'home' => array(
            'type' => 'Zend\Mvc\Router\Http\Literal',
            'options' => array(
                'route' => '/',
                'defaults' => array(
                    'controller' => 'index',
                    'action'     => 'index',
                ),
            ),
        ),
    ),
), (file_exists(__DIR__ . '/config.' . APPLICATION_ENV . '.php')) ? include __DIR__ . '/config.' . APPLICATION_ENV . '.php' : array());