<?php

namespace dpd\Type;

use Phpro\SoapClient\Type\RequestInterface;

class GetAvailableServicesResponseType implements RequestInterface
{

    /**
     * @var \dpd\Type\ServicesType
     */
    private $services;

    /**
     * Constructor
     *
     * @var \dpd\Type\ServicesType $services
     */
    public function __construct($services)
    {
        $this->services = $services;
    }

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

