<?php

namespace lujie\dpd\soap\Type;

use yii\base\BaseObject;

class ServicesType extends BaseObject
{
    /**
     * @var \lujie\dpd\soap\Type\ServiceType
     */
    private $service;

    /**
     * @return \lujie\dpd\soap\Type\ServiceType
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * @param \lujie\dpd\soap\Type\ServiceType $service
     * @return ServicesType
     */
    public function withService($service)
    {
        $new = clone $this;
        $new->service = $service;

        return $new;
    }

    /**
     * @param \lujie\dpd\soap\Type\ServiceType $service
     * @return $this
     */
    public function setService($service) : \lujie\dpd\soap\Type\ServicesType
    {
        $this->service = $service;
        return $this;
    }
}

