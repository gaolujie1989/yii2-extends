<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\remote\rbac\tests\unit;

use lujie\currency\exchanging\rateLoaders\SwapCurrencyExchangeRateLoader;
use yii\helpers\VarDumper;

class SwapCurrencyExchangeRateLoaderTest extends \Codeception\Test\Unit
{


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
        $swapRateLoader = new SwapCurrencyExchangeRateLoader([
            'swapServices' => [
                'fixer' => [
                    'access_key' => '53bbbeac6486ae0885cd5969b3e510a9'
                ],
                'currency_layer' => [
                    'access_key' => '83b0ee2572f1a20200dc2be602fb666a',
                    'enterprise' => false
                ],
            ]
        ]);
        $rate = $swapRateLoader->getRate('EUR', 'USD');
        $this->assertTrue($rate > 1 && $rate < 1.2, 'Swap Rate: ' . VarDumper::dumpAsString($rate));
        $rate = $swapRateLoader->getRate('EUR', 'USD', '2019-01-01');
        $this->assertTrue($rate > 1 && $rate < 1.2, 'Swap Rate: ' . VarDumper::dumpAsString($rate));
    }

    /**
     * @throws \Exception
     * @inheritdoc
     */
    public function testJuhe(): void
    {
        $swapRateLoader = new SwapCurrencyExchangeRateLoader([
            'swapServices' => [
                'juhe' => [
                    'access_key' => '8132ee950b073eca54515a83dd9e9229',
                ]
            ]
        ]);
        $rate = $swapRateLoader->getRate('EUR', 'USD');
        $this->assertTrue($rate > 1 && $rate < 1.2, 'Swap Rate: ' . VarDumper::dumpAsString($rate));
    }
}
