<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\remote\rbac\tests\unit;

use lujie\barcode\generating\BarcodeGeneratorInterface;
use lujie\barcode\generating\BCGBarcodeGenerator;
use lujie\currency\exchanging\rateLoaders\SwapCurrencyExchangeRateLoader;
use yii\helpers\VarDumper;

class SwapCurrencyExchangeRateLoaderTest extends \Codeception\Test\Unit
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
     * @throws \Exception
     * @inheritdoc
     */
    public function testMe(): void
    {
        $swapRateLoader = new SwapCurrencyExchangeRateLoader();
        $rate = $swapRateLoader->getRate('EUR', 'USD');
        $this->assertTrue($rate > 1 && $rate < 1.2, 'Swap Rate: ' . VarDumper::dumpAsString($rate));
        $rate = $swapRateLoader->getRate('EUR', 'USD', '2019-01-01');
        $this->assertTrue($rate > 1 && $rate < 1.2, 'Swap Rate: ' . VarDumper::dumpAsString($rate));
    }
}
