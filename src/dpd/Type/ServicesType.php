<?php

namespace dpd\Type;

use Phpro\SoapClient\Type\RequestInterface;

class ServicesType implements RequestInterface
{

    /**
     * @var \dpd\Type\ServiceType
     */
    private $service;

    /**
     * Constructor
     *
     * @var \dpd\Type\ServiceType $service
     */
    public function __construct($service)
    {
        $this->service = $service;
    }

    /**
     * @return \dpd\Type\ServiceType
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * @param \dpd\Type\ServiceType $service
     * @return ServicesType
     */
    public function withService($service)
    {
        $new = clone $this;
        $new->service = $service;

        return $new;
    }


}

