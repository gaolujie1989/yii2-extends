<?php

namespace dpd\Type;

class AddressWithType
{

    /**
     * @var string
     */
    private $addressType;

    /**
     * @return string
     */
    public function getAddressType()
    {
        return $this->addressType;
    }

    /**
     * @param string $addressType
     * @return AddressWithType
     */
    public function withAddressType($addressType)
    {
        $new = clone $this;
        $new->addressType = $addressType;

        return $new;
    }


}

