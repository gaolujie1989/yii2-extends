<?php

namespace dpd\Type;

use Phpro\SoapClient\Type\RequestInterface;

class ServiceDetailRequestType implements RequestInterface
{

    /**
     * @var string
     */
    private $code;

    /**
     * Constructor
     *
     * @var string $code
     */
    public function __construct($code)
    {
        $this->code = $code;
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
     * @return ServiceDetailRequestType
     */
    public function withCode($code)
    {
        $new = clone $this;
        $new->code = $code;

        return $new;
    }


}

