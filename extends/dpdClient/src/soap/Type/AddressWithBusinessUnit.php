<?php

namespace lujie\dpd\soap\Type;

class AddressWithBusinessUnit extends Address
{

    /**
     * @var int
     */
    private $businessUnit;

    /**
     * @return int
     */
    public function getBusinessUnit()
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

