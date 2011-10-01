<?php
return new Zend\Config\Config(array(
    'module_paths' => array(
        APPLICATION_PATH . '/modules',
    ),
    'modules' => array(
        'Buggy',
        'Admin',
    ),
    'module_config' => array( 
        'cache_config'  => false,
        'cache_dir'     => BASE_PATH . '/data/cache',
    ),
));