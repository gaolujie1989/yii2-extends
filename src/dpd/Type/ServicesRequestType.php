<?php

namespace dpd\Type;

use Phpro\SoapClient\Type\RequestInterface;

class ServicesRequestType implements RequestInterface
{

    /**
     * @var \dpd\Type\ServiceRequestType
     */
    private $service;

    /**
     * Constructor
     *
     * @var \dpd\Type\ServiceRequestType $service
     */
    public function __construct($service)
    {
        $this->service = $service;
    }

    /**
     * @return \dpd\Type\ServiceRequestType
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * @param \dpd\Type\ServiceRequestType $service
     * @return ServicesRequestType
     */
    public function withService($service)
    {
        $new = clone $this;
        $new->service = $service;

        return $new;
    }


}

