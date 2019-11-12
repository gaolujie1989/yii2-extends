<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\remote\rbac\tests\unit;

use lujie\currency\exchanging\CurrencyExchangeRateUpdater;
use lujie\currency\exchanging\models\CurrencyExchangeRate;
use lujie\currency\exchanging\tests\unit\mocks\MockCurrencyExchangeRateLoader;

class CurrencyExchangeRateUpdaterTest extends \Codeception\Test\Unit
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
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\Exception
     * @inheritdoc
     */
    public function testMe(): void
    {
        $rateIndex = static function ($values) {
            return $values['from'] . '/' . $values['to'];
        };
        $rateLoader = new MockCurrencyExchangeRateLoader();
        $updater = new CurrencyExchangeRateUpdater([
            'currencies' => ['EUR', 'USD'],
            'dateRange' => ['-1 day', 'now'],
            'rateLoader' => $rateLoader
        ]);
        $updater->updateRates();
        $rates = CurrencyExchangeRate::find()
            ->date(date('Y-m-d'))
            ->asArray()
            ->indexBy($rateIndex)
            ->all();
        $this->assertCount(2, $rates);
        $rates = CurrencyExchangeRate::find()
            ->date(date('Y-m-d', strtotime('-1 day')))
            ->asArray()
            ->indexBy($rateIndex)
            ->all();
        $this->assertCount(2, $rates);
        $this->assertEquals($rateLoader->rates['EUR/USD'], $rates['EUR/USD']);
        $this->assertEquals($rateLoader->rates['USD/EUR'], $rates['USD/EUR']);
    }
}
