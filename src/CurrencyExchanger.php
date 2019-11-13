<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\currency\exchanging;

use lujie\currency\exchanging\models\CurrencyExchangeRate;
use yii\base\BaseObject;

/**
 * Class CurrencyExchanger
 * @package lujie\currency\exchanging
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class CurrencyExchanger extends BaseObject
{
    /**
     * @var array
     */
    public $currencyAlias = [
        'RMB' => 'CNY',
    ];

    /**
     * @param string $from
     * @param string $to
     * @param string|int $date
     * @inheritdoc
     */
    public function getRate(string $from, string $to, $date = ''): ?float
    {
        $from = $this->currencyAlias[$from] ?? $from;
        $to = $this->currencyAlias[$to] ?? $to;
        if ($from === $to) {
            return 1;
        }

        if (empty($date)) {
            $date = date('Y-m-d');
        } else if (is_int($date)) {
            $date = date('Y-m-d', $date);
        }

        $query = CurrencyExchangeRate::find()
            ->fromTo($from, $to)
            ->beforeDate($date)
            ->orderByDate(SORT_DESC);
        return $query->getRate();
    }

    /**
     * @param string $from
     * @param string $to
     * @param float $amount
     * @param string|int $date
     * @return float|null
     * @inheritdoc
     */
    public function exchange(string $from, string $to, float $amount, $date = ''): ?float
    {
        $rate = $this->getRate($from, $to, $date);
        if ($rate === null) {
            return null;
        }
        return $amount * $rate;
    }
}
