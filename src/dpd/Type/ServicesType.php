<?php

namespace dpd\Type;

class ServicesType
{

    /**
     * @var \dpd\Type\ServiceType
     */
    private $service;

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

