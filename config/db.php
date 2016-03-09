<?php

$file = __DIR__.'/../test.sqlite';

return [
    'class' => 'yii\db\Connection',
    'dsn' => 'pgsql:host=localhost;port=5432;dbname=test',
    'username' => 'postgres',
    'password' => '',
    'charset' => 'utf8',
];
