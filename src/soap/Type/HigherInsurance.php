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
    public function getAmount() : int
    {
        return $this->amount;
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
     * @param int $amount
     * @return HigherInsurance
     */
    public function withAmount(int $amount) : \lujie\dpd\soap\Type\HigherInsurance
    {
        $new = clone $this;
        $new->amount = $amount;

        return $new;
    }

    /**
     * @return string
     */
    public function getCurrency() : string
    {
        return $this->currency;
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

    /**
     * @param string $currency
     * @return HigherInsurance
     */
    public function withCurrency(string $currency) : \lujie\dpd\soap\Type\HigherInsurance
    {
        $new = clone $this;
        $new->currency = $currency;

        return $new;
    }


}

