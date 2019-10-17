<?php
/**
 * @copyright Copyright (c) 2019
 */

//for fixing config auth AccessControl behavior with ActionAccessRule in config/main.php
include_once __DIR__ . '/src/filters/ActionAccessRule.php';

return [
    'lujie/yii2-auth' => [
        'name' => 'lujie/yii2-auth',
        'version' => 'dev-master',
        'alias' => [
            '@lujie/auth' => __DIR__ . '/src',
            '@lujie/auth/tests' => __DIR__ . '/tests',
        ]
    ],
];
