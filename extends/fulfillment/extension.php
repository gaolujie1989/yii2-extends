<?php
/**
 * @copyright Copyright (c) 2019
 */

return [
    'lujie/yii2-fulfillment' => [
        'name' => 'lujie/yii2-fulfillment',
        'version' => 'dev-master',
        'alias' => [
            '@lujie/fulfillment' => __DIR__ . '/src',
            '@lujie/fulfillment/tests' => __DIR__ . '/tests',
        ],
        'require' => [
            'lujie/yii2-data-exchange',
            'lujie/yii2-charging',
        ]
    ],
];
