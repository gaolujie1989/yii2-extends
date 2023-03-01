<?php

namespace dpd\Type;

class ServiceDetailRequestType
{

    /**
     * @var string
     */
    private $code;

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

