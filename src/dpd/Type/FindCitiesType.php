<?php

namespace dpd\Type;

use Phpro\SoapClient\Type\RequestInterface;

class FindCitiesType implements RequestInterface
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
     * Constructor
     *
     * @var string $country
     * @var string $zipCode
     * @var string $city
     * @var int $limit
     * @var string $order
     */
    public function __construct($country, $zipCode, $city, $limit, $order)
    {
        $this->country = $country;
        $this->zipCode = $zipCode;
        $this->city = $city;
        $this->limit = $limit;
        $this->order = $order;
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
     * @return FindCitiesType
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
     * @return FindCitiesType
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
     * @return FindCitiesType
     */
    public function withCity($city)
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
     * @return FindCitiesType
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
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @param string $order
     * @return FindCitiesType
     */
    public function withOrder($order)
    {
        $new = clone $this;
        $new->order = $order;

        return $new;
    }


}

