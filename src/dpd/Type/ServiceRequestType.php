<?php

namespace dpd\Type;

class ServiceRequestType
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
     * @var \dpd\Type\ServiceDetailRequestType
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
     * @return ServiceRequestType
     */
    public function withCode($code)
    {
        $new = clone $this;
        $new->code = $code;

        return $new;
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
     * @return ServiceRequestType
     */
    public function withAvailable($available)
    {
        $new = clone $this;
        $new->available = $available;

        return $new;
    }

    /**
     * @return \dpd\Type\ServiceDetailRequestType
     */
    public function getServiceDetail()
    {
        return $this->serviceDetail;
    }

    /**
     * @param \dpd\Type\ServiceDetailRequestType $serviceDetail
     * @return ServiceRequestType
     */
    public function withServiceDetail($serviceDetail)
    {
        $new = clone $this;
        $new->serviceDetail = $serviceDetail;

        return $new;
    }


}

