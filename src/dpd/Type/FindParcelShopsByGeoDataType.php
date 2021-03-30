<?php

namespace dpd\Type;

use Phpro\SoapClient\Type\RequestInterface;

class FindParcelShopsByGeoDataType implements RequestInterface
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
     * @var \dpd\Type\ServicesRequestType
     */
    private $services;

    /**
     * Constructor
     *
     * @var float $longitude
     * @var float $latitude
     * @var int $limit
     * @var string $availabilityDate
     * @var bool $hideClosed
     * @var string $searchCountry
     * @var \dpd\Type\ServicesRequestType $services
     */
    public function __construct($longitude, $latitude, $limit, $availabilityDate, $hideClosed, $searchCountry, $services)
    {
        $this->longitude = $longitude;
        $this->latitude = $latitude;
        $this->limit = $limit;
        $this->availabilityDate = $availabilityDate;
        $this->hideClosed = $hideClosed;
        $this->searchCountry = $searchCountry;
        $this->services = $services;
    }

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
     * @return \dpd\Type\ServicesRequestType
     */
    public function getServices()
    {
        return $this->services;
    }

    /**
     * @param \dpd\Type\ServicesRequestType $services
     * @return FindParcelShopsByGeoDataType
     */
    public function withServices($services)
    {
        $new = clone $this;
        $new->services = $services;

        return $new;
    }
}
