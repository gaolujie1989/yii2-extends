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
     * @return $this
     */
    public function setService($service) : \lujie\dpd\soap\Type\ServicesType
    {
        $this->service = $service;
        return $this;
    }

    /**
     * @param \lujie\dpd\soap\Type\ServiceType $service
     * @return ServicesType
     */
    public function withService(\lujie\dpd\soap\Type\ServiceType $service) : \lujie\dpd\soap\Type\ServicesType
    {
        $new = clone $this;
        $new->service = $service;

        return $new;
    }


}

