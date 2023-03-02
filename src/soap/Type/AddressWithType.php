<?php

namespace lujie\dpd\soap\Type;

use yii\base\BaseObject;

class AddressWithType extends BaseObject
{

    /**
     * @var string
     */
    private $addressType;

    /**
     * @return string
     */
    public function getAddressType() : string
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

