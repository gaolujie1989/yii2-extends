<?php

namespace dpd\Type;

use Phpro\SoapClient\Type\ResultInterface;

class FindCitiesResponseType implements ResultInterface
{

    /**
     * @var \dpd\Type\CityType
     */
    private $city;

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

