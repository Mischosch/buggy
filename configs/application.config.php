<?php
return array(
    'module_paths' => array(
        realpath(__DIR__ . '/../modules'),
    ),
    'modules' => array(
    	'ZfComposer',
    	'SpiffyDoctrine',
    	'SpiffyDoctrineExtensions',
 		'Buggy',
    	'BuggyAdmin',
		'EdpCommon',
    	'EdpUser',
    	'EdpMarkdown',
    ),
    'module_listener_options' => array( 
        'config_cache_enabled'      => false,
        'cache_dir'                => realpath(__DIR__ . '/../data/cache'),
        //'enable_dependency_check'  => false,
        //'enable_auto_installation' => false,
        //'manifest_dir'             => realpath(__DIR__ . '/../data'),
    ),
);
