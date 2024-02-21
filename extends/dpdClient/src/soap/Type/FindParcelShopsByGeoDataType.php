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
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * @param float $longitude
     * @return FindParcelShopsByGeoDataType
     */
    public function withLongitude($longitude)
    {
        $new = clone $this;
        $new->longitude = $longitude;

        return $new;
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
     * @return float
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * @param float $latitude
     * @return FindParcelShopsByGeoDataType
     */
    public function withLatitude($latitude)
    {
        $new = clone $this;
        $new->latitude = $latitude;

        return $new;
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
     * @return int
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * @param int $limit
     * @return FindParcelShopsByGeoDataType
     */
    public function withLimit($limit)
    {
        $new = clone $this;
        $new->limit = $limit;

        return $new;
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
     * @return string
     */
    public function getAvailabilityDate()
    {
        return $this->availabilityDate;
    }

    /**
     * @param string $availabilityDate
     * @return FindParcelShopsByGeoDataType
     */
    public function withAvailabilityDate($availabilityDate)
    {
        $new = clone $this;
        $new->availabilityDate = $availabilityDate;

        return $new;
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
     * @return bool
     */
    public function getHideClosed()
    {
        return $this->hideClosed;
    }

    /**
     * @param bool $hideClosed
     * @return FindParcelShopsByGeoDataType
     */
    public function withHideClosed($hideClosed)
    {
        $new = clone $this;
        $new->hideClosed = $hideClosed;

        return $new;
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
     * @return string
     */
    public function getSearchCountry()
    {
        return $this->searchCountry;
    }

    /**
     * @param string $searchCountry
     * @return FindParcelShopsByGeoDataType
     */
    public function withSearchCountry($searchCountry)
    {
        $new = clone $this;
        $new->searchCountry = $searchCountry;

        return $new;
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
     * @return \lujie\dpd\soap\Type\ServicesRequestType
     */
    public function getServices()
    {
        return $this->services;
    }

    /**
     * @param \lujie\dpd\soap\Type\ServicesRequestType $services
     * @return FindParcelShopsByGeoDataType
     */
    public function withServices($services)
    {
        $new = clone $this;
        $new->services = $services;

        return $new;
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
}

