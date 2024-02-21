<?php

namespace lujie\dpd\soap\Type;

use yii\base\BaseObject;
use Phpro\SoapClient\Type\RequestInterface;

class FindParcelShopsType extends BaseObject implements RequestInterface
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
     * @var \lujie\dpd\soap\Type\ServicesRequestType
     */
    private $services;

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
     * @param string $country
     * @return $this
     */
    public function setCountry(string $country) : \lujie\dpd\soap\Type\FindParcelShopsType
    {
        $this->country = $country;
        return $this;
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
     * @param string $zipCode
     * @return $this
     */
    public function setZipCode(string $zipCode) : \lujie\dpd\soap\Type\FindParcelShopsType
    {
        $this->zipCode = $zipCode;
        return $this;
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
     * @param string $city
     * @return $this
     */
    public function setCity(string $city) : \lujie\dpd\soap\Type\FindParcelShopsType
    {
        $this->city = $city;
        return $this;
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
     * @param string $street
     * @return $this
     */
    public function setStreet(string $street) : \lujie\dpd\soap\Type\FindParcelShopsType
    {
        $this->street = $street;
        return $this;
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
     * @param string $houseNo
     * @return $this
     */
    public function setHouseNo(string $houseNo) : \lujie\dpd\soap\Type\FindParcelShopsType
    {
        $this->houseNo = $houseNo;
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
     * @return FindParcelShopsType
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
    public function setLimit(int $limit) : \lujie\dpd\soap\Type\FindParcelShopsType
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
     * @return FindParcelShopsType
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
    public function setAvailabilityDate(string $availabilityDate) : \lujie\dpd\soap\Type\FindParcelShopsType
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
     * @return FindParcelShopsType
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
    public function setHideClosed(bool $hideClosed) : \lujie\dpd\soap\Type\FindParcelShopsType
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
     * @return FindParcelShopsType
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
    public function setSearchCountry(string $searchCountry) : \lujie\dpd\soap\Type\FindParcelShopsType
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
     * @return FindParcelShopsType
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
    public function setServices($services) : \lujie\dpd\soap\Type\FindParcelShopsType
    {
        $this->services = $services;
        return $this;
    }
}

