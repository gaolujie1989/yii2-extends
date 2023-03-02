<?php

namespace lujie\dpd\soap\Type;

class AddressWithType extends AddressWithBusinessUnit
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
     * @return $this
     */
    public function setAddressType(string $addressType) : \lujie\dpd\soap\Type\AddressWithType
    {
        $this->addressType = $addressType;
        return $this;
    }

    /**
     * @param string $addressType
     * @return AddressWithType
     */
    public function withAddressType(string $addressType) : \lujie\dpd\soap\Type\AddressWithType
    {
        $new = clone $this;
        $new->addressType = $addressType;

        return $new;
    }


}

