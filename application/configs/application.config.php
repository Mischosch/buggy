<?php
return new Zend\Config\Config(array(
    'modulePaths' => array(
        APPLICATION_PATH . '/modules', 
        realpath(__DIR__ . '/../../modules'),
    ),
    'modules' => array(
        'Zf2Module', // @todo: really need it?
        'Zf2Mvc',
        'Buggy',
        //'Admin',
    ),
));