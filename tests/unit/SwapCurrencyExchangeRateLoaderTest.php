<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\remote\rbac\tests\unit;

use lujie\barcode\generating\BarcodeGeneratorInterface;
use lujie\barcode\generating\BCGBarcodeGenerator;
use lujie\currency\exchanging\rateLoaders\SwapCurrencyExchangeRateLoader;

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
        $rate = $swapRateLoader->getRate('USD', 'EUR');
        $this->assertTrue($rate > 1 && $rate < 1.2);
        $rate = $swapRateLoader->getRate('USD', 'EUR', '2019-01-01');
        $this->assertTrue($rate > 1 && $rate < 1.2);
    }
}
