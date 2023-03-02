<?php

namespace lujie\dpd\soap\Type;

use yii\base\BaseObject;

class CityType extends BaseObject
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
     * @return $this
     */
    public function setCountry(string $country) : \lujie\dpd\soap\Type\CityType
    {
        $this->country = $country;
        return $this;
    }

    /**
     * @param string $country
     * @return CityType
     */
    public function withCountry(string $country) : \lujie\dpd\soap\Type\CityType
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
     * @return $this
     */
    public function setCountryNum(int $countryNum) : \lujie\dpd\soap\Type\CityType
    {
        $this->countryNum = $countryNum;
        return $this;
    }

    /**
     * @param int $countryNum
     * @return CityType
     */
    public function withCountryNum(int $countryNum) : \lujie\dpd\soap\Type\CityType
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
     * @return $this
     */
    public function setZipCode(string $zipCode) : \lujie\dpd\soap\Type\CityType
    {
        $this->zipCode = $zipCode;
        return $this;
    }

    /**
     * @param string $zipCode
     * @return CityType
     */
    public function withZipCode(string $zipCode) : \lujie\dpd\soap\Type\CityType
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
     * @return $this
     */
    public function setName(string $name) : \lujie\dpd\soap\Type\CityType
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @param string $name
     * @return CityType
     */
    public function withName(string $name) : \lujie\dpd\soap\Type\CityType
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
     * @return $this
     */
    public function setTown(string $town) : \lujie\dpd\soap\Type\CityType
    {
        $this->town = $town;
        return $this;
    }

    /**
     * @param string $town
     * @return CityType
     */
    public function withTown(string $town) : \lujie\dpd\soap\Type\CityType
    {
        $new = clone $this;
        $new->town = $town;

        return $new;
    }


}

