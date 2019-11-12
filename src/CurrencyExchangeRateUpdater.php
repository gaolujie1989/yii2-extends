<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\currency\exchanging;


use lujie\currency\exchanging\models\CurrencyExchangeRate;
use yii\base\BaseObject;
use yii\di\Instance;

/**
 * Class CurrencyExchangeRateUpdater
 * @package lujie\currency\exchanging
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class CurrencyExchangeRateUpdater extends BaseObject
{
    /**
     * @var array
     */
    public $currencies = ['EUR', 'USD', 'GBP', 'CNY'];

    /**
     * @var array
     */
    public $dateRange = ['now', 'now'];

    /**
     * @var bool
     */
    public $skipOnExist = true;

    /**
     * @var CurrencyExchangeRateLoader
     */
    public $rateLoader;

    /**
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\Exception
     * @inheritdoc
     */
    public function updateRates(): void
    {
        $this->rateLoader = Instance::ensure($this->rateLoader, CurrencyExchangeRateLoader::class);
        $timeFrom = strtotime($this->dateRange[0]);
        $timeTo = strtotime($this->dateRange[1]);
        for ($time = $timeFrom; $time <= $timeTo; $time += 86400) {
            $date = date('Y-m-d', $time);
            foreach ($this->currencies as $currencyFrom) {
                foreach ($this->currencies as $currencyTo) {
                    if ($currencyFrom === $currencyTo) {
                        continue;
                    }
                    $query = CurrencyExchangeRate::find()
                        ->fromTo($currencyFrom, $currencyTo)
                        ->date($date);
                    $exchangeRate = $query->one();
                    if ($this->skipOnExist && $exchangeRate) {
                        continue;
                    }

                    if ($exchangeRate === null) {
                        $exchangeRate = new CurrencyExchangeRate();
                        $exchangeRate->from = $currencyFrom;
                        $exchangeRate->to = $currencyTo;
                        $exchangeRate->date = $date;
                    }
                    $rate = $this->rateLoader->getRate($currencyFrom, $currencyTo, $date);
                    $exchangeRate->rate = $rate;
                    $exchangeRate->mustSave(false);
                }
            }
        }
    }
}
