<?php
/**
 * @copyright Copyright (c) 2019
 */

return [
    'lujie/yii2-barcode-generator' => [
        'name' => 'lujie/yii2-barcode-generator',
        'version' => 'dev-master',
        'alias' => [
            '@lujie/barcode/generating' => __DIR__ . '/src',
            '@lujie/barcode/generating/tests' => __DIR__ . '/tests',
            '@BarcodeBakery/Common' => __DIR__ . '/src/barcodegen6.0.0/packages/barcode-common/src',
            '@BarcodeBakery/Barcode' => __DIR__ . '/src/barcodegen6.0.0/packages/barcode-1d/src',
        ]
    ],
];
