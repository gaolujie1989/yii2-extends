<?php

namespace dpd\Type;

class CityType
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

