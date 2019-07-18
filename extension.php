<?php
/**
 * @copyright Copyright (c) 2019
 */

return [
    'lujie/yii2-barcode' => [
        'name' => 'lujie/yii2-barcode',
        'version' => 'dev-master',
        'alias' => [
            '@lujie/barcode' => __DIR__ . '/src',
            '@lujie/barcode/tests' => __DIR__ . '/tests',
            '@BarcodeBakery/Common' => __DIR__ . '/src/barcodegen6.0.0/packages/barcode-common/src',
            '@BarcodeBakery/Barcode' => __DIR__ . '/src/barcodegen6.0.0/packages/barcode-1d/src',
        ]
    ],
];
