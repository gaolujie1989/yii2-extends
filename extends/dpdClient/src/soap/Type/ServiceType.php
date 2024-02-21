<?php

namespace lujie\dpd\soap\Type;

use yii\base\BaseObject;

class ServiceType extends BaseObject
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
     * @var string
     */
    private $description;

    /**
     * @var \lujie\dpd\soap\Type\ServiceDetailType
     */
    private $serviceDetail;

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param string $code
     * @return ServiceType
     */
    public function withCode($code)
    {
        $new = clone $this;
        $new->code = $code;

        return $new;
    }

    /**
     * @param string $code
     * @return $this
     */
    public function setCode(string $code) : \lujie\dpd\soap\Type\ServiceType
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @return bool
     */
    public function getAvailable()
    {
        return $this->available;
    }

    /**
     * @param bool $available
     * @return ServiceType
     */
    public function withAvailable($available)
    {
        $new = clone $this;
        $new->available = $available;

        return $new;
    }

    /**
     * @param bool $available
     * @return $this
     */
    public function setAvailable(bool $available) : \lujie\dpd\soap\Type\ServiceType
    {
        $this->available = $available;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return ServiceType
     */
    public function withDescription($description)
    {
        $new = clone $this;
        $new->description = $description;

        return $new;
    }

    /**
     * @param string $description
     * @return $this
     */
    public function setDescription(string $description) : \lujie\dpd\soap\Type\ServiceType
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return \lujie\dpd\soap\Type\ServiceDetailType
     */
    public function getServiceDetail()
    {
        return $this->serviceDetail;
    }

    /**
     * @param \lujie\dpd\soap\Type\ServiceDetailType $serviceDetail
     * @return ServiceType
     */
    public function withServiceDetail($serviceDetail)
    {
        $new = clone $this;
        $new->serviceDetail = $serviceDetail;

        return $new;
    }

    /**
     * @param \lujie\dpd\soap\Type\ServiceDetailType $serviceDetail
     * @return $this
     */
    public function setServiceDetail($serviceDetail) : \lujie\dpd\soap\Type\ServiceType
    {
        $this->serviceDetail = $serviceDetail;
        return $this;
    }
}

