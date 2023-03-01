<?php

namespace dpd\Type;

use Phpro\SoapClient\Type\ResultInterface;

class FindParcelShopsByGeoDataResponseType implements ResultInterface
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
     * @return FindParcelShopsByGeoDataResponseType
     */
    public function withParcelShop($parcelShop)
    {
        $new = clone $this;
        $new->parcelShop = $parcelShop;

        return $new;
    }


}

