<?php

namespace lujie\dpd\soap\Type;

use yii\base\BaseObject;
use Phpro\SoapClient\Type\ResultInterface;

class FindCitiesResponseType extends BaseObject implements ResultInterface
{

    /**
     * @var \lujie\dpd\soap\Type\CityType
     */
    private $city;

    /**
     * @return \lujie\dpd\soap\Type\CityType
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param \lujie\dpd\soap\Type\CityType $city
     * @return $this
     */
    public function setCity($city) : \lujie\dpd\soap\Type\FindCitiesResponseType
    {
        $this->city = $city;
        return $this;
    }

    /**
     * @param \lujie\dpd\soap\Type\CityType $city
     * @return FindCitiesResponseType
     */
    public function withCity(\lujie\dpd\soap\Type\CityType $city) : \lujie\dpd\soap\Type\FindCitiesResponseType
    {
        $new = clone $this;
        $new->city = $city;

        return $new;
    }


}

