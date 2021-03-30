<?php

namespace dpd\Type;

use Phpro\SoapClient\Type\RequestInterface;

class FindParcelShopsType implements RequestInterface
{

    /**
     * @var string
     */
    private $country;

    /**
     * @var string
     */
    private $zipCode;

    /**
     * @var string
     */
    private $city;

    /**
     * @var string
     */
    private $street;

    /**
     * @var string
     */
    private $houseNo;

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
     * @var string $country
     * @var string $zipCode
     * @var string $city
     * @var string $street
     * @var string $houseNo
     * @var int $limit
     * @var string $availabilityDate
     * @var bool $hideClosed
     * @var string $searchCountry
     * @var \dpd\Type\ServicesRequestType $services
     */
    public function __construct($country, $zipCode, $city, $street, $houseNo, $limit, $availabilityDate, $hideClosed, $searchCountry, $services)
    {
        $this->country = $country;
        $this->zipCode = $zipCode;
        $this->city = $city;
        $this->street = $street;
        $this->houseNo = $houseNo;
        $this->limit = $limit;
        $this->availabilityDate = $availabilityDate;
        $this->hideClosed = $hideClosed;
        $this->searchCountry = $searchCountry;
        $this->services = $services;
    }

    /**
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param string $country
     * @return FindParcelShopsType
     */
    public function withCountry($country)
    {
        $new = clone $this;
        $new->country = $country;

        return $new;
    }

    /**
     * @return string
     */
    public function getZipCode()
    {
        return $this->zipCode;
    }

    /**
     * @param string $zipCode
     * @return FindParcelShopsType
     */
    public function withZipCode($zipCode)
    {
        $new = clone $this;
        $new->zipCode = $zipCode;

        return $new;
    }

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param string $city
     * @return FindParcelShopsType
     */
    public function withCity($city)
    {
        $new = clone $this;
        $new->city = $city;

        return $new;
    }

    /**
     * @return string
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * @param string $street
     * @return FindParcelShopsType
     */
    public function withStreet($street)
    {
        $new = clone $this;
        $new->street = $street;

        return $new;
    }

    /**
     * @return string
     */
    public function getHouseNo()
    {
        return $this->houseNo;
    }

    /**
     * @param string $houseNo
     * @return FindParcelShopsType
     */
    public function withHouseNo($houseNo)
    {
        $new = clone $this;
        $new->houseNo = $houseNo;

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
     * @return FindParcelShopsType
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
     * @return FindParcelShopsType
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
     * @return FindParcelShopsType
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
     * @return FindParcelShopsType
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
     * @return FindParcelShopsType
     */
    public function withServices($services)
    {
        $new = clone $this;
        $new->services = $services;

        return $new;
    }
}
