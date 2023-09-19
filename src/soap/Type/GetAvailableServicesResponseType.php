<?php

namespace lujie\dpd\soap\Type;

use yii\base\BaseObject;
use Phpro\SoapClient\Type\ResultInterface;

class GetAvailableServicesResponseType extends BaseObject implements ResultInterface
{
    /**
     * @var \lujie\dpd\soap\Type\ServicesType
     */
    private $services;

    /**
     * @return \lujie\dpd\soap\Type\ServicesType
     */
    public function getServices()
    {
        return $this->services;
    }

    /**
     * @param \lujie\dpd\soap\Type\ServicesType $services
     * @return GetAvailableServicesResponseType
     */
    public function withServices($services)
    {
        $new = clone $this;
        $new->services = $services;

        return $new;
    }

    /**
     * @param \lujie\dpd\soap\Type\ServicesType $services
     * @return $this
     */
    public function setServices($services) : \lujie\dpd\soap\Type\GetAvailableServicesResponseType
    {
        $this->services = $services;
        return $this;
    }
}

