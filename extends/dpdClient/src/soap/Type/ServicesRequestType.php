<?php

namespace lujie\dpd\soap\Type;

use yii\base\BaseObject;

class ServicesRequestType extends BaseObject
{
    /**
     * @var \lujie\dpd\soap\Type\ServiceRequestType
     */
    private $service;

    /**
     * @return \lujie\dpd\soap\Type\ServiceRequestType
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * @param \lujie\dpd\soap\Type\ServiceRequestType $service
     * @return ServicesRequestType
     */
    public function withService($service)
    {
        $new = clone $this;
        $new->service = $service;

        return $new;
    }

    /**
     * @param \lujie\dpd\soap\Type\ServiceRequestType $service
     * @return $this
     */
    public function setService($service) : \lujie\dpd\soap\Type\ServicesRequestType
    {
        $this->service = $service;
        return $this;
    }
}

