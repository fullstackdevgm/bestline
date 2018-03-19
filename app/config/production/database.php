<?php

return array(
    'connections' => array(
        'mysql' => array(
            'driver'    => 'mysql',
            'host'      => $_SERVER['RDS_HOSTNAME'],
            'database'  => $_SERVER['RDS_DB_NAME'],
            'username'  => $_SERVER['RDS_USERNAME'],
            'password'  => $_SERVER['RDS_PASSWORD'],
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
        ),
    ),
);