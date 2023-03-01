<?php

namespace dpd\Type;

class ServicesRequestType
{

    /**
     * @var \dpd\Type\ServiceRequestType
     */
    private $service;

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

