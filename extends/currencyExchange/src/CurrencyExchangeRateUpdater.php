<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\currency\exchanging;

use lujie\currency\exchanging\models\CurrencyExchangeRate;
use lujie\extend\caching\CachingTrait;
use yii\base\BaseObject;
use yii\di\Instance;

/**
 * Class CurrencyExchangeRateUpdater
 * @package lujie\currency\exchanging
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class CurrencyExchangeRateUpdater extends BaseObject
{
    use CachingTrait;

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
     * @var string
     */
    public $baseCurrency = 'EUR';

    /**
     * @var string
     */
    public $cacheKeyPrefix = 'CurrencyExchangeRateUpdater';

    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        $this->rateLoader = Instance::ensure($this->rateLoader, CurrencyExchangeRateLoader::class);
    }

    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function updateRates(): void
    {
        $timeFrom = strtotime($this->dateRange[0]);
        $timeTo = strtotime($this->dateRange[1]);
        for ($time = $timeFrom; $time <= $timeTo; $time += 86400) {
            $date = date('Y-m-d', $time);
            if (!is_array(reset($this->currencies))) {
                $this->currencies = [$this->currencies];
            }
            foreach ($this->currencies as $groupCurrencies) {
                foreach ($groupCurrencies as $currencyFrom) {
                    foreach ($groupCurrencies as $currencyTo) {
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
                        $exchangeRate->rate = $this->getRate($currencyFrom, $currencyTo, $date);
                        $exchangeRate->save(false);
                    }
                }
            }
        }
    }

    /**
     * @param string $from
     * @param string $to
     * @param string|null $date
     * @return float
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function getRate(string $from, string $to, ?string $date = null): float
    {
        if ($this->baseCurrency) {
            if ($from === $this->baseCurrency) {
                $fromRate = 1;
            } else {
                $fromCacheKey = implode('/', [$this->baseCurrency, $from, $date]);
                $fromRate = $this->getOrSet($fromCacheKey, function () use ($from, $date) {
                    return $this->rateLoader->getRate($this->baseCurrency, $from, $date);
                });
            }
            if ($to === $this->baseCurrency) {
                $toRate = 1;
            } else {
                $toCacheKey = implode('/', [$this->baseCurrency, $to, $date]);
                $toRate = $this->getOrSet($toCacheKey, function () use ($to, $date) {
                    return $this->rateLoader->getRate($this->baseCurrency, $to, $date);
                });
            }
            return $toRate / $fromRate;
        }

        $cacheKey = implode('/', [$from, $to, $date]);
        return $this->getOrSet($cacheKey, function () use ($from, $to, $date) {
            return $this->rateLoader->getRate($from, $to, $date);
        });
    }
}
