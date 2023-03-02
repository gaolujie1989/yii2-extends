<?php

namespace lujie\dpd\soap\Type;

use yii\base\BaseObject;
use Phpro\SoapClient\Type\RequestInterface;

class FindParcelShopsByGeoDataType extends BaseObject implements RequestInterface
{

    /**
     * @var float
     */
    private $longitude;

    /**
     * @var float
     */
    private $latitude;

    /**
     * @var int
     */
    private $limit;

    /**
     * @var string
     */
    private $availabilityDate;

    /**
     * @var bool
     */
    private $hideClosed;

    /**
     * @var string
     */
    private $searchCountry;

    /**
     * @var \lujie\dpd\soap\Type\ServicesRequestType
     */
    private $services;

    /**
     * @return float
     */
    public function getLongitude() : float
    {
        return $this->longitude;
    }

    /**
     * @param float $longitude
     * @return $this
     */
    public function setLongitude(float $longitude) : \lujie\dpd\soap\Type\FindParcelShopsByGeoDataType
    {
        $this->longitude = $longitude;
        return $this;
    }

    /**
     * @param float $longitude
     * @return FindParcelShopsByGeoDataType
     */
    public function withLongitude(float $longitude) : \lujie\dpd\soap\Type\FindParcelShopsByGeoDataType
    {
        $new = clone $this;
        $new->longitude = $longitude;

        return $new;
    }

    /**
     * @return float
     */
    public function getLatitude() : float
    {
        return $this->latitude;
    }

    /**
     * @param float $latitude
     * @return $this
     */
    public function setLatitude(float $latitude) : \lujie\dpd\soap\Type\FindParcelShopsByGeoDataType
    {
        $this->latitude = $latitude;
        return $this;
    }

    /**
     * @param float $latitude
     * @return FindParcelShopsByGeoDataType
     */
    public function withLatitude(float $latitude) : \lujie\dpd\soap\Type\FindParcelShopsByGeoDataType
    {
        $new = clone $this;
        $new->latitude = $latitude;

        return $new;
    }

    /**
     * @return int
     */
    public function getLimit() : int
    {
        return $this->limit;
    }

    /**
     * @param int $limit
     * @return $this
     */
    public function setLimit(int $limit) : \lujie\dpd\soap\Type\FindParcelShopsByGeoDataType
    {
        $this->limit = $limit;
        return $this;
    }

    /**
     * @param int $limit
     * @return FindParcelShopsByGeoDataType
     */
    public function withLimit(int $limit) : \lujie\dpd\soap\Type\FindParcelShopsByGeoDataType
    {
        $new = clone $this;
        $new->limit = $limit;

        return $new;
    }

    /**
     * @return string
     */
    public function getAvailabilityDate() : string
    {
        return $this->availabilityDate;
    }

    /**
     * @param string $availabilityDate
     * @return $this
     */
    public function setAvailabilityDate(string $availabilityDate) : \lujie\dpd\soap\Type\FindParcelShopsByGeoDataType
    {
        $this->availabilityDate = $availabilityDate;
        return $this;
    }

    /**
     * @param string $availabilityDate
     * @return FindParcelShopsByGeoDataType
     */
    public function withAvailabilityDate(string $availabilityDate) : \lujie\dpd\soap\Type\FindParcelShopsByGeoDataType
    {
        $new = clone $this;
        $new->availabilityDate = $availabilityDate;

        return $new;
    }

    /**
     * @return bool
     */
    public function getHideClosed() : bool
    {
        return $this->hideClosed;
    }

    /**
     * @param bool $hideClosed
     * @return $this
     */
    public function setHideClosed(bool $hideClosed) : \lujie\dpd\soap\Type\FindParcelShopsByGeoDataType
    {
        $this->hideClosed = $hideClosed;
        return $this;
    }

    /**
     * @param bool $hideClosed
     * @return FindParcelShopsByGeoDataType
     */
    public function withHideClosed(bool $hideClosed) : \lujie\dpd\soap\Type\FindParcelShopsByGeoDataType
    {
        $new = clone $this;
        $new->hideClosed = $hideClosed;

        return $new;
    }

    /**
     * @return string
     */
    public function getSearchCountry() : string
    {
        return $this->searchCountry;
    }

    /**
     * @param string $searchCountry
     * @return $this
     */
    public function setSearchCountry(string $searchCountry) : \lujie\dpd\soap\Type\FindParcelShopsByGeoDataType
    {
        $this->searchCountry = $searchCountry;
        return $this;
    }

    /**
     * @param string $searchCountry
     * @return FindParcelShopsByGeoDataType
     */
    public function withSearchCountry(string $searchCountry) : \lujie\dpd\soap\Type\FindParcelShopsByGeoDataType
    {
        $new = clone $this;
        $new->searchCountry = $searchCountry;

        return $new;
    }

    /**
     * @return \lujie\dpd\soap\Type\ServicesRequestType
     */
    public function getServices() : \lujie\dpd\soap\Type\ServicesRequestType
    {
        return $this->services;
    }

    /**
     * @param \lujie\dpd\soap\Type\ServicesRequestType $services
     * @return $this
     */
    public function setServices($services) : \lujie\dpd\soap\Type\FindParcelShopsByGeoDataType
    {
        $this->services = $services;
        return $this;
    }

    /**
     * @param \lujie\dpd\soap\Type\ServicesRequestType $services
     * @return FindParcelShopsByGeoDataType
     */
    public function withServices(\lujie\dpd\soap\Type\ServicesRequestType $services) : \lujie\dpd\soap\Type\FindParcelShopsByGeoDataType
    {
        $new = clone $this;
        $new->services = $services;

        return $new;
    }


}

