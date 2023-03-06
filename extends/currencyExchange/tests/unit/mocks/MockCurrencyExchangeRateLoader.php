<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\currency\exchanging\tests\unit\mocks;

use lujie\currency\exchanging\CurrencyExchangeRateLoader;
use yii\base\BaseObject;

/**
 * Class MockCurrencyExchangeRateLoader
 * @package lujie\currency\exchanging\tests\unit\mocks
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class MockCurrencyExchangeRateLoader extends BaseObject implements CurrencyExchangeRateLoader
{
    public $rates = [
        'EUR/USD' => '1.0993',
        'EUR/GBP' => '0.8836',
        'EUR/CNY' => '7.8098',
//        'USD/EUR' => '0.9097',
//        'USD/GBP' => '0.8038',
//        'USD/CNY' => '7.1043',
//        'GBP/EUR' => '1.1317',
//        'GBP/USD' => '1.2441',
//        'GBP/CNY' => '8.8385',
//        'CNY/EUR' => '0.1280',
//        'CNY/USD' => '0.1408',
//        'CNY/GBP' => '0.1131',
    ];

    /**
     * @param string $from
     * @param string $to
     * @param string $date
     * @return float
     * @inheritdoc
     */
    public function getRate(string $from, string $to, string $date = ''): float
    {
        return $this->rates[$from . '/' . $to] ?? 0;
    }
}
