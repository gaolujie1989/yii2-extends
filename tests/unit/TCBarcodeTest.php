<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\remote\rbac\tests\unit;

use lujie\barcode\BarcodeInterface;
use lujie\barcode\TCBarcode;

class TCBarcodeTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    protected function _before()
    {
    }

    protected function _after()
    {
    }

    /**
     * @throws \Picqer\Barcode\Exceptions\BarcodeException
     * @throws \yii\base\NotSupportedException
     * @inheritdoc
     */
    public function testMe(): void
    {
        $dir = __DIR__ . '/../_output/';
        $barcode = new TCBarcode();
        $image = $barcode->generateBarcodeImage(BarcodeInterface::CODE128, 'CODE128ABC123456');
        file_put_contents($dir . 'tc.code128.png', $image);
        $image = $barcode->generateBarcodeImage(BarcodeInterface::EAN13, '4251249498714');
        file_put_contents($dir . 'tc.ean13.png', $image);
    }
}
