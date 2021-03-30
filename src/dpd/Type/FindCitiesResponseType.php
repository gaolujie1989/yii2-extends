<?php

namespace dpd\Type;

use Phpro\SoapClient\Type\RequestInterface;

class FindCitiesResponseType implements RequestInterface
{

    /**
     * @var \dpd\Type\CityType
     */
    private $city;

    /**
     * Constructor
     *
     * @var \dpd\Type\CityType $city
     */
    public function __construct($city)
    {
        $this->city = $city;
    }

    /**
     * @return \dpd\Type\CityType
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param \dpd\Type\CityType $city
     * @return FindCitiesResponseType
     */
    public function withCity($city)
    {
        $new = clone $this;
        $new->city = $city;

        return $new;
    }
}
