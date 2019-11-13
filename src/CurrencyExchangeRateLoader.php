<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\currency\exchanging;


interface CurrencyExchangeRateLoader
{
    /**
     * @param string $from
     * @param string $to
     * @param string $date
     * @return float
     * @inheritdoc
     */
    public function getRate(string $from, string $to, string $date = ''): float;
}
