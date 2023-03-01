<?php

namespace dpd\Type;

class AddressWithBusinessUnit
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
     * @return AddressWithBusinessUnit
     */
    public function withBusinessUnit($businessUnit)
    {
        $new = clone $this;
        $new->businessUnit = $businessUnit;

        return $new;
    }


}

