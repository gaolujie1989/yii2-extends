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
    public function getCode() : string
    {
        return $this->code;
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
     * @param string $code
     * @return ServiceType
     */
    public function withCode(string $code) : \lujie\dpd\soap\Type\ServiceType
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
    public function setAvailable(bool $available) : \lujie\dpd\soap\Type\ServiceType
    {
        $this->available = $available;
        return $this;
    }

    /**
     * @param bool $available
     * @return ServiceType
     */
    public function withAvailable(bool $available) : \lujie\dpd\soap\Type\ServiceType
    {
        $new = clone $this;
        $new->available = $available;

        return $new;
    }

    /**
     * @return string
     */
    public function getDescription() : string
    {
        return $this->description;
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
     * @param string $description
     * @return ServiceType
     */
    public function withDescription(string $description) : \lujie\dpd\soap\Type\ServiceType
    {
        $new = clone $this;
        $new->description = $description;

        return $new;
    }

    /**
     * @return \lujie\dpd\soap\Type\ServiceDetailType
     */
    public function getServiceDetail() : \lujie\dpd\soap\Type\ServiceDetailType
    {
        return $this->serviceDetail;
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

    /**
     * @param \lujie\dpd\soap\Type\ServiceDetailType $serviceDetail
     * @return ServiceType
     */
    public function withServiceDetail(\lujie\dpd\soap\Type\ServiceDetailType $serviceDetail) : \lujie\dpd\soap\Type\ServiceType
    {
        $new = clone $this;
        $new->serviceDetail = $serviceDetail;

        return $new;
    }


}

