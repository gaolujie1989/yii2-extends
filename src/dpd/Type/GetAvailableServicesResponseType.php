<?php

namespace dpd\Type;

use Phpro\SoapClient\Type\ResultInterface;

class GetAvailableServicesResponseType implements ResultInterface
{

    /**
     * @var \dpd\Type\ServicesType
     */
    private $services;

    /**
     * @return \dpd\Type\ServicesType
     */
    public function getServices()
    {
        return $this->services;
    }

    /**
     * @param \dpd\Type\ServicesType $services
     * @return GetAvailableServicesResponseType
     */
    public function withServices($services)
    {
        $new = clone $this;
        $new->services = $services;

        return $new;
    }


}

