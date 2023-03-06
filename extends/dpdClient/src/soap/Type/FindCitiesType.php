<?php

namespace lujie\dpd\soap\Type;

use yii\base\BaseObject;
use Phpro\SoapClient\Type\RequestInterface;

class FindCitiesType extends BaseObject implements RequestInterface
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
     * @var int
     */
    private $limit;

    /**
     * @var string
     */
    private $order;

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
    public function setCountry(string $country) : \lujie\dpd\soap\Type\FindCitiesType
    {
        $this->country = $country;
        return $this;
    }

    /**
     * @param string $country
     * @return FindCitiesType
     */
    public function withCountry(string $country) : \lujie\dpd\soap\Type\FindCitiesType
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
     * @return $this
     */
    public function setZipCode(string $zipCode) : \lujie\dpd\soap\Type\FindCitiesType
    {
        $this->zipCode = $zipCode;
        return $this;
    }

    /**
     * @param string $zipCode
     * @return FindCitiesType
     */
    public function withZipCode(string $zipCode) : \lujie\dpd\soap\Type\FindCitiesType
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
     * @return $this
     */
    public function setCity(string $city) : \lujie\dpd\soap\Type\FindCitiesType
    {
        $this->city = $city;
        return $this;
    }

    /**
     * @param string $city
     * @return FindCitiesType
     */
    public function withCity(string $city) : \lujie\dpd\soap\Type\FindCitiesType
    {
        $new = clone $this;
        $new->city = $city;

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
     * @return $this
     */
    public function setLimit(int $limit) : \lujie\dpd\soap\Type\FindCitiesType
    {
        $this->limit = $limit;
        return $this;
    }

    /**
     * @param int $limit
     * @return FindCitiesType
     */
    public function withLimit(int $limit) : \lujie\dpd\soap\Type\FindCitiesType
    {
        $new = clone $this;
        $new->limit = $limit;

        return $new;
    }

    /**
     * @return string
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @param string $order
     * @return $this
     */
    public function setOrder(string $order) : \lujie\dpd\soap\Type\FindCitiesType
    {
        $this->order = $order;
        return $this;
    }

    /**
     * @param string $order
     * @return FindCitiesType
     */
    public function withOrder(string $order) : \lujie\dpd\soap\Type\FindCitiesType
    {
        $new = clone $this;
        $new->order = $order;

        return $new;
    }


}

