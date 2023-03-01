<?php

namespace dpd\Type;

class AuthenticationFault
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
    public function getErrorCode()
    {
        return $this->errorCode;
    }

    /**
     * @param string $errorCode
     * @return AuthenticationFault
     */
    public function withErrorCode($errorCode)
    {
        $new = clone $this;
        $new->errorCode = $errorCode;

        return $new;
    }

    /**
     * @return string
     */
    public function getErrorMessage()
    {
        return $this->errorMessage;
    }

    /**
     * @param string $errorMessage
     * @return AuthenticationFault
     */
    public function withErrorMessage($errorMessage)
    {
        $new = clone $this;
        $new->errorMessage = $errorMessage;

        return $new;
    }


}

