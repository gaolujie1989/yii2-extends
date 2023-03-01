<?php

namespace dpd\Type;

use Phpro\SoapClient\Type\ResultInterface;

class FindParcelShopsResponseType implements ResultInterface
{

    /**
     * @var \dpd\Type\ParcelShopType
     */
    private $parcelShop;

    /**
     * @return \dpd\Type\ParcelShopType
     */
    public function getParcelShop()
    {
        return $this->parcelShop;
    }

    /**
     * @param \dpd\Type\ParcelShopType $parcelShop
     * @return FindParcelShopsResponseType
     */
    public function withParcelShop($parcelShop)
    {
        $new = clone $this;
        $new->parcelShop = $parcelShop;

        return $new;
    }


}

