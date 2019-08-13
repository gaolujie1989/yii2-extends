<?php

namespace dpd\Type;

use Phpro\SoapClient\Type\RequestInterface;

class CityType implements RequestInterface
{

    /**
     * @var string
     */
    private $country;

    /**
     * @var int
     */
    private $countryNum;

    /**
     * @var string
     */
    private $zipCode;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $town;

    /**
     * Constructor
     *
     * @var string $country
     * @var int $countryNum
     * @var string $zipCode
     * @var string $name
     * @var string $town
     */
    public function __construct($country, $countryNum, $zipCode, $name, $town)
    {
        $this->country = $country;
        $this->countryNum = $countryNum;
        $this->zipCode = $zipCode;
        $this->name = $name;
        $this->town = $town;
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
     * @return CityType
     */
    public function withCountry($country)
    {
        $new = clone $this;
        $new->country = $country;

        return $new;
    }

    /**
     * @return int
     */
    public function getCountryNum()
    {
        return $this->countryNum;
    }

    /**
     * @param int $countryNum
     * @return CityType
     */
    public function withCountryNum($countryNum)
    {
        $new = clone $this;
        $new->countryNum = $countryNum;

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
     * @return CityType
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
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return CityType
     */
    public function withName($name)
    {
        $new = clone $this;
        $new->name = $name;

        return $new;
    }

    /**
     * @return string
     */
    public function getTown()
    {
        return $this->town;
    }

    /**
     * @param string $town
     * @return CityType
     */
    public function withTown($town)
    {
        $new = clone $this;
        $new->town = $town;

        return $new;
    }


}

