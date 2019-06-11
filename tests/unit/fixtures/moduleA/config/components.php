<?php

return [
    'testDb' => [
        'class' => 'yii\db\Connection',
        'dsn' => 'mysql:host=mysql;dbname=test',
        'username' => 'root',
        'password' => 'root',
        'charset' => 'utf8mb4',
        'enableSchemaCache' => true,
    ],

    'backend' => [
        'testDb' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=mysql;dbname=test_backend',
            'username' => 'root',
            'password' => 'root',
            'charset' => 'utf8mb4',
            'enableSchemaCache' => true,
        ]
    ],
    'console' => [
        'testDb' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=mysql;dbname=test_console',
            'username' => 'root',
            'password' => 'root',
            'charset' => 'utf8mb4',
            'enableSchemaCache' => true,
        ]
    ],
];
