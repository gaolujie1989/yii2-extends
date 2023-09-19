<?php

namespace lujie\dpd\soap\Type;

use yii\base\BaseObject;
use Phpro\SoapClient\Type\ResultInterface;

class FindParcelShopsByGeoDataResponseType extends BaseObject implements ResultInterface
{
    /**
     * @var \lujie\dpd\soap\Type\ParcelShopType
     */
    private $parcelShop;

    /**
     * @return \lujie\dpd\soap\Type\ParcelShopType
     */
    public function getParcelShop()
    {
        return $this->parcelShop;
    }

    /**
     * @param \lujie\dpd\soap\Type\ParcelShopType $parcelShop
     * @return FindParcelShopsByGeoDataResponseType
     */
    public function withParcelShop($parcelShop)
    {
        $new = clone $this;
        $new->parcelShop = $parcelShop;

        return $new;
    }

    /**
     * @param \lujie\dpd\soap\Type\ParcelShopType $parcelShop
     * @return $this
     */
    public function setParcelShop($parcelShop) : \lujie\dpd\soap\Type\FindParcelShopsByGeoDataResponseType
    {
        $this->parcelShop = $parcelShop;
        return $this;
    }
}

