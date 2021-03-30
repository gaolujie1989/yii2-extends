<?php

namespace dpd\Type;

use Phpro\SoapClient\Type\RequestInterface;

class Fault implements RequestInterface
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
     * Constructor
     *
     * @var string $errorCode
     * @var string $errorMessage
     */
    public function __construct($errorCode, $errorMessage)
    {
        $this->errorCode = $errorCode;
        $this->errorMessage = $errorMessage;
    }

    /**
     * @return string
     */
    public function getErrorCode()
    {
        return $this->errorCode;
    }

    /**
     * @param string $errorCode
     * @return Fault
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
     * @return Fault
     */
    public function withErrorMessage($errorMessage)
    {
        $new = clone $this;
        $new->errorMessage = $errorMessage;

        return $new;
    }
}
