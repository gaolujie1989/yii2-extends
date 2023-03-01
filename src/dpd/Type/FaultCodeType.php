<?php

namespace dpd\Type;

class FaultCodeType
{

    /**
     * @var string
     */
    private $faultCode;

    /**
     * @var string
     */
    private $message;

    /**
     * @return string
     */
    public function getFaultCode()
    {
        return $this->faultCode;
    }

    /**
     * @param string $faultCode
     * @return FaultCodeType
     */
    public function withFaultCode($faultCode)
    {
        $new = clone $this;
        $new->faultCode = $faultCode;

        return $new;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param string $message
     * @return FaultCodeType
     */
    public function withMessage($message)
    {
        $new = clone $this;
        $new->message = $message;

        return $new;
    }


}

