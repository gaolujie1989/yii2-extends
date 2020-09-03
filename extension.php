<?php
/**
 * @copyright Copyright (c) 2019
 */

return [
    'lujie/yii2-data-recording' => [
        'name' => 'lujie/yii2-data-recording',
        'version' => 'dev-master',
        'alias' => [
            '@lujie/data/recording' => __DIR__ . '/src',
            '@lujie/data/recording/tests' => __DIR__ . '/tests',
        ],
        'require' => [
            'lujie/yii2-data-exchange',
        ]
    ],
];
