<?php

namespace lujie\dpd\soap\Type;

use yii\base\BaseObject;

class ServiceRequestType extends BaseObject
{

    /**
     * @var string
     */
    private $code;

    /**
     * @var bool
     */
    private $available;

    /**
     * @var \lujie\dpd\soap\Type\ServiceDetailRequestType
     */
    private $serviceDetail;

    /**
     * @return string
     */
    public function getCode() : string
    {
        return $this->code;
    }

    /**
     * @param string $code
     * @return $this
     */
    public function setCode(string $code) : \lujie\dpd\soap\Type\ServiceRequestType
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @param string $code
     * @return ServiceRequestType
     */
    public function withCode(string $code) : \lujie\dpd\soap\Type\ServiceRequestType
    {
        $new = clone $this;
        $new->code = $code;

        return $new;
    }

    /**
     * @return bool
     */
    public function getAvailable() : bool
    {
        return $this->available;
    }

    /**
     * @param bool $available
     * @return $this
     */
    public function setAvailable(bool $available) : \lujie\dpd\soap\Type\ServiceRequestType
    {
        $this->available = $available;
        return $this;
    }

    /**
     * @param bool $available
     * @return ServiceRequestType
     */
    public function withAvailable(bool $available) : \lujie\dpd\soap\Type\ServiceRequestType
    {
        $new = clone $this;
        $new->available = $available;

        return $new;
    }

    /**
     * @return \lujie\dpd\soap\Type\ServiceDetailRequestType
     */
    public function getServiceDetail() : \lujie\dpd\soap\Type\ServiceDetailRequestType
    {
        return $this->serviceDetail;
    }

    /**
     * @param \lujie\dpd\soap\Type\ServiceDetailRequestType $serviceDetail
     * @return $this
     */
    public function setServiceDetail($serviceDetail) : \lujie\dpd\soap\Type\ServiceRequestType
    {
        $this->serviceDetail = $serviceDetail;
        return $this;
    }

    /**
     * @param \lujie\dpd\soap\Type\ServiceDetailRequestType $serviceDetail
     * @return ServiceRequestType
     */
    public function withServiceDetail(\lujie\dpd\soap\Type\ServiceDetailRequestType $serviceDetail) : \lujie\dpd\soap\Type\ServiceRequestType
    {
        $new = clone $this;
        $new->serviceDetail = $serviceDetail;

        return $new;
    }


}

