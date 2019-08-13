<?php

namespace dpd\Type;

use Phpro\SoapClient\Type\RequestInterface;

class ServiceType implements RequestInterface
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
     * @var \dpd\Type\ServiceDetailType
     */
    private $serviceDetail;

    /**
     * Constructor
     *
     * @var string $code
     * @var bool $available
     * @var string $description
     * @var \dpd\Type\ServiceDetailType $serviceDetail
     */
    public function __construct($code, $available, $description, $serviceDetail)
    {
        $this->code = $code;
        $this->available = $available;
        $this->description = $description;
        $this->serviceDetail = $serviceDetail;
    }

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
     * @return \dpd\Type\ServiceDetailType
     */
    public function getServiceDetail()
    {
        return $this->serviceDetail;
    }

    /**
     * @param \dpd\Type\ServiceDetailType $serviceDetail
     * @return ServiceType
     */
    public function withServiceDetail($serviceDetail)
    {
        $new = clone $this;
        $new->serviceDetail = $serviceDetail;

        return $new;
    }


}

