<?php

namespace lujie\dpd\soap\Type;

use yii\base\BaseObject;

class HigherInsurance extends BaseObject
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
     * @param int $amount
     * @return $this
     */
    public function setAmount(int $amount) : \lujie\dpd\soap\Type\HigherInsurance
    {
        $this->amount = $amount;
        return $this;
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

    /**
     * @param string $currency
     * @return $this
     */
    public function setCurrency(string $currency) : \lujie\dpd\soap\Type\HigherInsurance
    {
        $this->currency = $currency;
        return $this;
    }
}

