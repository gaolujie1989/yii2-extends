<?php

namespace lujie\dpd\soap\Type;


use yii\base\BaseObject;

class AuthenticationFault extends BaseObject
{

    /**
     * @var string
     */
    private $errorCode;

    /**
     * @var string
     */
    private $errorMessage;

    /**
     * @return string
     */
    public function getErrorCode() : string
    {
        return $this->errorCode;
    }

    /**
     * @param string $errorCode
     * @return $this
     */
    public function setErrorCode(string $errorCode) : \lujie\dpd\soap\Type\AuthenticationFault
    {
        $this->errorCode = $errorCode;
        return $this;
    }

    /**
     * @param string $errorCode
     * @return AuthenticationFault
     */
    public function withErrorCode(string $errorCode) : \lujie\dpd\soap\Type\AuthenticationFault
    {
        $new = clone $this;
        $new->errorCode = $errorCode;

        return $new;
    }

    /**
     * @return string
     */
    public function getErrorMessage() : string
    {
        return $this->errorMessage;
    }

    /**
     * @param string $errorMessage
     * @return $this
     */
    public function setErrorMessage(string $errorMessage) : \lujie\dpd\soap\Type\AuthenticationFault
    {
        $this->errorMessage = $errorMessage;
        return $this;
    }

    /**
     * @param string $errorMessage
     * @return AuthenticationFault
     */
    public function withErrorMessage(string $errorMessage) : \lujie\dpd\soap\Type\AuthenticationFault
    {
        $new = clone $this;
        $new->errorMessage = $errorMessage;

        return $new;
    }


}

