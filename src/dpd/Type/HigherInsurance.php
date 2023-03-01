<?php

namespace dpd\Type;

class HigherInsurance
{

    /**
     * @var int
     */
    private $amount;

    /**
     * @var string
     */
    private $currency;

    /**
     * @return int
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param int $amount
     * @return HigherInsurance
     */
    public function withAmount($amount)
    {
        $new = clone $this;
        $new->amount = $amount;

        return $new;
    }

    /**
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @param string $currency
     * @return HigherInsurance
     */
    public function withCurrency($currency)
    {
        $new = clone $this;
        $new->currency = $currency;

        return $new;
    }


}

