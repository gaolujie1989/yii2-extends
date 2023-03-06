<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\remote\rbac\tests\unit;

use lujie\currency\exchanging\CurrencyExchanger;
use lujie\currency\exchanging\tests\unit\fixtures\CurrencyExchangeRateFixture;

class CurrencyExchangerTest extends \Codeception\Test\Unit
{
    protected function _before()
    {
    }

    protected function _after()
    {
    }

    public function _fixtures(): array
    {
        return [
            'rate' => CurrencyExchangeRateFixture::class
        ];
    }

    /**
     * @inheritdoc
     */
    public function testMe(): void
    {
        $currencyExchanger = new CurrencyExchanger();
        $rate = $currencyExchanger->getRate('CNY', 'RMB');
        $this->assertEquals(1, $rate);
        $exchangedAmount = $currencyExchanger->exchange('CNY', 'RMB', 12.34);
        $this->assertEquals(12.34, $exchangedAmount);

        $currencyExchanger = new CurrencyExchanger();
        $rate = $currencyExchanger->getRate('EUR', 'USD');
        $this->assertEquals(1.0993, $rate);
        $rate = $currencyExchanger->getRate('EUR', 'USD', '2019-01-01');
        $this->assertEquals(1.1770, $rate);
        $exchangedAmount = $currencyExchanger->exchange('EUR', 'USD', 100);
        $this->assertEquals(109.93, $exchangedAmount);
        $exchangedAmount = $currencyExchanger->exchange('EUR', 'USD', 100, '2019-01-01');
        $this->assertEquals(117.70, $exchangedAmount);
    }
}
