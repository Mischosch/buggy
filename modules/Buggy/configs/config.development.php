<?php
return array(
    'display_exceptions' => 1,
    'di' => array(
        'instance' => array(
            'doctrine_connection' => array(
                'parameters' => array(
                    'params' => array(
                        'driver'   => 'pdo_mysql',
                        'host'     => 'localhost',
                        'port'     => '3306',
                        'user'     => 'root',
                        'password' => '',
                        'dbname'   => 'buggy',
                    ),
                )
            ),
        ),
    ),
);