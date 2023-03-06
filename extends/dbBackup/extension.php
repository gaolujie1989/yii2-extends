<?php
/**
 * @copyright Copyright (c) 2019
 */

return [
    'lujie/yii2-backup-manager-driver' => [
        'name' => 'lujie/yii2-backup-manager-driver',
        'version' => 'dev-master',
        'alias' => [
            '@lujie/backup/manager' => __DIR__ . '/src',
            '@lujie/backup/manager/tests' => __DIR__ . '/tests',
        ],
        'require' => [
            'lujie/yii2-executing',
        ]
    ],
];
