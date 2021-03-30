<?php

namespace dpd\Type;

use Phpro\SoapClient\Type\RequestInterface;

class FindParcelShopsResponseType implements RequestInterface
{

    /**
     * @var \dpd\Type\ParcelShopType
     */
    private $parcelShop;

    /**
     * Constructor
     *
     * @var \dpd\Type\ParcelShopType $parcelShop
     */
    public function __construct($parcelShop)
    {
        $this->parcelShop = $parcelShop;
    }

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
