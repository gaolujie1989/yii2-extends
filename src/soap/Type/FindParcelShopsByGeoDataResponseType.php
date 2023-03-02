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
    public function getParcelShop() : \lujie\dpd\soap\Type\ParcelShopType
    {
        return $this->parcelShop;
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

    /**
     * @param \lujie\dpd\soap\Type\ParcelShopType $parcelShop
     * @return FindParcelShopsByGeoDataResponseType
     */
    public function withParcelShop(\lujie\dpd\soap\Type\ParcelShopType $parcelShop) : \lujie\dpd\soap\Type\FindParcelShopsByGeoDataResponseType
    {
        $new = clone $this;
        $new->parcelShop = $parcelShop;

        return $new;
    }


}

