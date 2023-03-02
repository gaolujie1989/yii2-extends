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
    public function getFaultCode() : string
    {
        return $this->faultCode;
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
     * @param string $faultCode
     * @return FaultCodeType
     */
    public function withFaultCode(string $faultCode) : \lujie\dpd\soap\Type\FaultCodeType
    {
        $new = clone $this;
        $new->faultCode = $faultCode;

        return $new;
    }

    /**
     * @return string
     */
    public function getMessage() : string
    {
        return $this->message;
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

    /**
     * @param string $message
     * @return FaultCodeType
     */
    public function withMessage(string $message) : \lujie\dpd\soap\Type\FaultCodeType
    {
        $new = clone $this;
        $new->message = $message;

        return $new;
    }


}

