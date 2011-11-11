<?php
return array(
    'buggy' => array(
    ),
    'di' => array(
        'instance' => array(
            'alias' => array(
                'buggy'                  => 'Buggy\Controller\IndexController',
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
            'doctrine_evm' => array(
                'parameters' => array(
                    'opts' => array(
                        'subscribers' => array(
                        	 'Gedmo\Timestampable\TimestampableListener',
    				'Gedmo\Sluggable\SluggableListener', 
    				'Gedmo\Tree\TreeListener'
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
                            'user' => __DIR__ . '/../views',
                        ),
                    ),
                ),
            ),
        ),
    ),
);
