<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\currency\exchanging\rateLoaders;

use DateTime;
use lujie\currency\exchanging\CurrencyExchangeRateLoader;
use lujie\currency\exchanging\swap\services\Juhe;
use Swap\Builder;
use Swap\Service\Registry;
use Swap\Swap;
use yii\base\BaseObject;

/**
 * Class SwapCurrencyExchangeRateLoader
 * @package lujie\currency\exchanging
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class SwapCurrencyExchangeRateLoader extends BaseObject implements CurrencyExchangeRateLoader
{
    /**
     * @var array
     */
    public $swapServices = [
        'fixer' => [
            'access_key' => '53bbbeac6486ae0885cd5969b3e510a9'
        ],
        'currency_layer' => [
            'access_key' => '83b0ee2572f1a20200dc2be602fb666a',
            'enterprise' => false
        ],
        'juhe' => [
            'access_key' => '8132ee950b073eca54515a83dd9e9229',
        ]
    ];

    /**
     * @var array
     */
    public $registerServices = [
        'juhe' => Juhe::class,
    ];

    /**
     * @var Swap
     */
    private $swap;

    /**
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        foreach ($this->registerServices as $service => $class) {
            Registry::register($service, $class);
        }
        $builder = new Builder();
        foreach ($this->swapServices as $service => $options) {
            $builder->add($service, $options);
        }
        $this->swap = $builder->build();
    }

    /**
     * @param string $from
     * @param string $to
     * @param string|null $date
     * @return float
     * @throws \Exception
     * @inheritdoc
     */
    public function getRate(string $from, string $to, ?string $date = null): float
    {
        $currencyPair = $from . '/' . $to;
        if (empty($date) || $date === date('Y-m-d')) {
            $exchangeRate = $this->swap->latest($currencyPair);
            return $exchangeRate->getValue();
        }

        $dateTime = new DateTime($date);
        $exchangeRate = $this->swap->historical($currencyPair, $dateTime);
        return $exchangeRate->getValue();
    }
}
