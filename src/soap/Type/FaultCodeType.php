<?php

namespace lujie\dpd\soap\Type;

use yii\base\BaseObject;

class FaultCodeType extends BaseObject
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
     * @param string $faultCode
     * @return $this
     */
    public function setFaultCode(string $faultCode) : \lujie\dpd\soap\Type\FaultCodeType
    {
        $this->faultCode = $faultCode;
        return $this;
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

    /**
     * @param string $message
     * @return $this
     */
    public function setMessage(string $message) : \lujie\dpd\soap\Type\FaultCodeType
    {
        $this->message = $message;
        return $this;
    }
}

