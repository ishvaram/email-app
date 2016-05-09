<?php

return array(
    'db' => array(
        'driver'         => 'Pdo',
        'dsn'            => 'mysql:dbname=sms;host=127.0.0.1',
        'username'       => 'root',
        'password'       => 'root',
        'driver_options' => array(
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''
        ),
    ),
    'db_storage' => array(
        'table' => 'tmp',
        'column_key' => 'key',
        'column_value' => 'value',
    ),
);
