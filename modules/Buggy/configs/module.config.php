<?php
$config = array(
    'display_exceptions' => 1,
    'layout' => 'layouts/buggy.phtml',
    'buggy' => array(
    ),
    'spiffy-doctrine-extensions' => array(
        'timestampable' => true,
    ),
    'spiffy-doctrine-annotations' => __DIR__ . '/../../../vendor/doctrine/orm/lib/Doctrine/ORM/Mapping/Driver/DoctrineAnnotations.php',
    'zfcouchdb-annotations' => __DIR__ . '/../../../vendor/doctrine/couchdb-odm/lib/Doctrine/ODM/CouchDB/Mapping/Annotations/',
    'spiffy-doctrine-extensions-path' => __DIR__ . '/../../../vendor/gedmo/doctrine-extensions/lib/',
    'di' => array(
        'instance' => array(
            'alias' => array(
                'index'    => 'Buggy\Controller\IndexController',
                'error'    => 'Buggy\Controller\ErrorController',
                'view'     => 'Zend\View\PhpRenderer',
                'markdown' => 'Markdown_Parser',
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
            'Buggy\Controller\IndexController' => array(
                'parameters' => array(
                    'dm' => 'doctrine_dm'
                )
            ),
            'doctrine_connection' => array(
                'parameters' => array(
                    'params' => array(
                        'driver'   => 'pdo_mysql',
                        'host'     => 'localhost',
                        'port'     => '3306',
                        'user'     => 'root',
                        'password' => 'local',
                        'dbname'   => 'buggy',
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
/*                        'buggy_document_annotationdriver' => array(
                            'class'           => 'Doctrine\ODM\CouchDb\Mapping\Driver\AnnotationDriver',
                            'namespace'       => 'Buggy\Document',
                            'paths'           => array(__DIR__ . '/../src/Buggy/Document'),
                        ),*/
                    ),
                )
            ),

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
if (file_exists(__DIR__ . '/config.' . $env . '.php')) {
    $config = new Zend\Config\Config($config, true);
    $config->merge(new Zend\Config\Config(include __DIR__ . '/config.' . APPLICATION_ENV . '.php'));
    return $config->toArray();
}
return $config;