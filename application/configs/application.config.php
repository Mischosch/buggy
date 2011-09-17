<?php
return new Zend\Config\Config(array(
    'module_paths' => array(
        APPLICATION_PATH . '/modules', 
        realpath(__DIR__ . '/../../modules'),
    ),
    'modules' => array(
        'Zf2Module', // @todo: really need it?
        'Zf2Mvc',
        'Buggy',
        'Admin',
    ),
    'module_config' => array( 
        'cache_config'  => false,
        'cache_dir'     => realpath(__DIR__ . '/../../data/cache'),
    ),
));