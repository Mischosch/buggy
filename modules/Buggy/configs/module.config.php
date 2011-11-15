<?php
return array(
    'display_exceptions' => 1,
    'layout' => 'layouts/buggy.phtml',
    'buggy' => array(
    ),
    'spiffy-doctrine-extensions' => array(
        'timestampable' => true,
    ),
    'di' => array(
        'instance' => array(
            'alias' => array(
                'index'    => 'Buggy\Controller\IndexController',
                'error'    => 'Buggy\Controller\ErrorController',
                'view'     => 'Zend\View\PhpRenderer',
				'markdown' => 'Markdown_Parser',
            ),

            'doctrine' => array(
                'parameters' => array(
                    'conn' => array(
                        'driver'   => 'pdo_mysql',
                        'host'     => 'localhost',
                        'port'     => '3306', 
                        'user'     => 'root',
                        'password' => 'local',
                        'dbname'   => 'buggy2',
                    ),
                )
            ),

            'Zend\View\HelperLoader' => array(
                'parameters' => array(
                    'map' => array(
                        'url'               => 'Buggy\View\Helper\Url',
                        'markdown'          => 'EdpMarkdown\View\Helper\Markdown',
                        'messagesFormatter' => 'Buggy\View\Helper\MessagesFormatter',
                    ),
                ),
            ),

            'Zend\View\HelperBroker' => array( 
                'parameters' => array( 
                    'loader' => 'Zend\View\HelperLoader', 
                ), 
            ),

            'Zend\View\PhpRenderer' => array(
                'parameters' => array(
            		'broker' => 'Zend\View\HelperBroker',
                    'resolver' => 'Zend\View\TemplatePathStack',
                    'options'  => array(
                        'script_paths' => array(
                            'application' => __DIR__ . '/../views',
                        ),
                    ),
                ),
            ),

      		'doctrine_connection' => array(
                'parameters' => array(
                    'params' => array(
                        'driver'   => 'pdo_mysql',
                        'host'     => 'localhost',
                        'port'     => '3306', 
                        'user'     => 'root',
                        'password' => 'local',
                        'dbname'   => 'buggy2',
                    ),
                    'config' => 'doctrine_config',
                    'evm'    => 'doctrine_evm'
                )
            ),
            'doctrine_driver_chain' => array(
                'parameters' => array(
                    'drivers' => array(
						'buggybase_annotationdriver' => array(
							'class'           => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
							'namespace'       => 'Buggy\ModelBase',
							'paths'           => array(__DIR__ . '/../src/Buggy/ModelBase'),
						),
                        'buggy_annotationdriver' => array(
                            'class'           => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                            'namespace'       => 'Buggy\Model',
                            'paths'           => array(__DIR__ . '/../src/Buggy/Model'),
                        ),
                    ),
                )
            ),
            'doctrine_config' => array(
            ),
            'doctrine_evm' => array(
                'parameters' => array(
                    'opts' => array(
                        'subscribers' => array(
                        	 'Gedmo\Timestampable\TimestampableListener'
    					)
                    )
                )
            ),
            'doctrine-eventmanager' => array(
                'injections' => array(
                    'Gedmo\Timestampable\TimestampableListener',
    				'Gedmo\Sluggable\SluggableListener', 
    				'Gedmo\Tree\TreeListener'
                ),
            ),
            /*'doctrine-eventmanager' => array(
                'injections' => array(
                        'Gedmo\Timestampable\Timestampable',
    					'Gedmo\Sluggable\SluggableListener', 
    					'Gedmo\Tree\TreeListener', 
    					'DoctrineExtensions\Versionable\VersionListener'
                ),
            ),
            'Buggy\Mapper\ProjectDoctrine' => array(
                'parameters' => array(
                    'em' => 'doctrine_em'
                ),
            ),*/
            'Zend\View\PhpRenderer' => array(
                'parameters' => array(
                    'options'  => array(
                        'script_paths' => array(
                            'buggy' => __DIR__ . '/../views',
                        ),
                    ),
                ),
            ),
        ),
    ),
    'routes' => array(
        'default' => array(
            'type'    => 'Zend\Mvc\Router\Http\Segment',
            'options' => array(
                'route'    => '/[:controller[/:action]]',
                'constraints' => array(
                    'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                ),
                'defaults' => array(
                    'controller' => 'index',
                    'action'     => 'index',
                ),
            ),
        ),
        'home' => array(
            'type' => 'Zend\Mvc\Router\Http\Literal',
            'options' => array(
                'route'    => '/',
                'defaults' => array(
                    'controller' => 'index',
                    'action'     => 'index',
                ),
            ),
        ),
    ),
);
