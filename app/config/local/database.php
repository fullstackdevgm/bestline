<?php

return array(
    'connections' => array(
        'mysql' => array(
            'driver'    => 'mysql',
            'host'      => (getenv('DB_HOST') !== null)? getenv('DB_HOST') : 'localhost',
            'database'  => 'bestline',
            'username'  => 'root',
            'password'  => '123',
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
        ),
    ),
);