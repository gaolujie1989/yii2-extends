<?php

namespace lujie\dpd\soap\Type;

use yii\base\BaseObject;

class AddressWithBusinessUnit extends BaseObject
{

    /**
     * @var int
     */
    private $businessUnit;

    /**
     * @return int
     */
    public function getBusinessUnit() : int
    {
        return $this->businessUnit;
    }

    /**
     * @param int $businessUnit
     * @return $this
     */
    public function setBusinessUnit(int $businessUnit) : \lujie\dpd\soap\Type\AddressWithBusinessUnit
    {
        $this->businessUnit = $businessUnit;
        return $this;
    }

    /**
     * @param int $businessUnit
     * @return AddressWithBusinessUnit
     */
    public function withBusinessUnit(int $businessUnit) : \lujie\dpd\soap\Type\AddressWithBusinessUnit
    {
        $new = clone $this;
        $new->businessUnit = $businessUnit;

        return $new;
    }


}

