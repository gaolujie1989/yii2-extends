<?php
/**
 * @copyright Copyright (c) 2019
 */

/** @noinspection ClassConstantCanBeUsedInspection */
return [
    'lujie/yii2-extend' => [
        'name' => 'lujie/yii2-extend',
        'version' => 'dev-master',
        'alias' => [
            '@lujie/extend' => __DIR__ . '/src',
            '@lujie/extend/tests' => __DIR__ . '/tests',
        ],
        'bootstrap' => 'lujie\extend\ExtendInitBootstrap'
    ],
];
