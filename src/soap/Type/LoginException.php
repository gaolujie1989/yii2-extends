<?php

namespace lujie\dpd\soap\Type;

use yii\base\BaseObject;

class LoginException extends BaseObject
{

    /**
     * @var string
     */
    private $additionalData;

    /**
     * @var string
     */
    private $additionalInfo;

    /**
     * @var string
     */
    private $errorClass;

    /**
     * @var string
     */
    private $errorCode;

    /**
     * @var string
     */
    private $fullMessage;

    /**
     * @var string
     */
    private $language;

    /**
     * @var string
     */
    private $message;

    /**
     * @var string
     */
    private $shortMessage;

    /**
     * @var string
     */
    private $systemFullMessage;

    /**
     * @var string
     */
    private $systemMessage;

    /**
     * @var string
     */
    private $systemShortMessage;

    /**
     * @return string
     */
    public function getAdditionalData()
    {
        return $this->additionalData;
    }

    /**
     * @param string $additionalData
     * @return $this
     */
    public function setAdditionalData(string $additionalData) : \lujie\dpd\soap\Type\LoginException
    {
        $this->additionalData = $additionalData;
        return $this;
    }

    /**
     * @param string $additionalData
     * @return LoginException
     */
    public function withAdditionalData(string $additionalData) : \lujie\dpd\soap\Type\LoginException
    {
        $new = clone $this;
        $new->additionalData = $additionalData;

        return $new;
    }

    /**
     * @return string
     */
    public function getAdditionalInfo()
    {
        return $this->additionalInfo;
    }

    /**
     * @param string $additionalInfo
     * @return $this
     */
    public function setAdditionalInfo(string $additionalInfo) : \lujie\dpd\soap\Type\LoginException
    {
        $this->additionalInfo = $additionalInfo;
        return $this;
    }

    /**
     * @param string $additionalInfo
     * @return LoginException
     */
    public function withAdditionalInfo(string $additionalInfo) : \lujie\dpd\soap\Type\LoginException
    {
        $new = clone $this;
        $new->additionalInfo = $additionalInfo;

        return $new;
    }

    /**
     * @return string
     */
    public function getErrorClass()
    {
        return $this->errorClass;
    }

    /**
     * @param string $errorClass
     * @return $this
     */
    public function setErrorClass(string $errorClass) : \lujie\dpd\soap\Type\LoginException
    {
        $this->errorClass = $errorClass;
        return $this;
    }

    /**
     * @param string $errorClass
     * @return LoginException
     */
    public function withErrorClass(string $errorClass) : \lujie\dpd\soap\Type\LoginException
    {
        $new = clone $this;
        $new->errorClass = $errorClass;

        return $new;
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
     * @return $this
     */
    public function setErrorCode(string $errorCode) : \lujie\dpd\soap\Type\LoginException
    {
        $this->errorCode = $errorCode;
        return $this;
    }

    /**
     * @param string $errorCode
     * @return LoginException
     */
    public function withErrorCode(string $errorCode) : \lujie\dpd\soap\Type\LoginException
    {
        $new = clone $this;
        $new->errorCode = $errorCode;

        return $new;
    }

    /**
     * @return string
     */
    public function getFullMessage()
    {
        return $this->fullMessage;
    }

    /**
     * @param string $fullMessage
     * @return $this
     */
    public function setFullMessage(string $fullMessage) : \lujie\dpd\soap\Type\LoginException
    {
        $this->fullMessage = $fullMessage;
        return $this;
    }

    /**
     * @param string $fullMessage
     * @return LoginException
     */
    public function withFullMessage(string $fullMessage) : \lujie\dpd\soap\Type\LoginException
    {
        $new = clone $this;
        $new->fullMessage = $fullMessage;

        return $new;
    }

    /**
     * @return string
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * @param string $language
     * @return $this
     */
    public function setLanguage(string $language) : \lujie\dpd\soap\Type\LoginException
    {
        $this->language = $language;
        return $this;
    }

    /**
     * @param string $language
     * @return LoginException
     */
    public function withLanguage(string $language) : \lujie\dpd\soap\Type\LoginException
    {
        $new = clone $this;
        $new->language = $language;

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
     * @return $this
     */
    public function setMessage(string $message) : \lujie\dpd\soap\Type\LoginException
    {
        $this->message = $message;
        return $this;
    }

    /**
     * @param string $message
     * @return LoginException
     */
    public function withMessage(string $message) : \lujie\dpd\soap\Type\LoginException
    {
        $new = clone $this;
        $new->message = $message;

        return $new;
    }

    /**
     * @return string
     */
    public function getShortMessage()
    {
        return $this->shortMessage;
    }

    /**
     * @param string $shortMessage
     * @return $this
     */
    public function setShortMessage(string $shortMessage) : \lujie\dpd\soap\Type\LoginException
    {
        $this->shortMessage = $shortMessage;
        return $this;
    }

    /**
     * @param string $shortMessage
     * @return LoginException
     */
    public function withShortMessage(string $shortMessage) : \lujie\dpd\soap\Type\LoginException
    {
        $new = clone $this;
        $new->shortMessage = $shortMessage;

        return $new;
    }

    /**
     * @return string
     */
    public function getSystemFullMessage()
    {
        return $this->systemFullMessage;
    }

    /**
     * @param string $systemFullMessage
     * @return $this
     */
    public function setSystemFullMessage(string $systemFullMessage) : \lujie\dpd\soap\Type\LoginException
    {
        $this->systemFullMessage = $systemFullMessage;
        return $this;
    }

    /**
     * @param string $systemFullMessage
     * @return LoginException
     */
    public function withSystemFullMessage(string $systemFullMessage) : \lujie\dpd\soap\Type\LoginException
    {
        $new = clone $this;
        $new->systemFullMessage = $systemFullMessage;

        return $new;
    }

    /**
     * @return string
     */
    public function getSystemMessage()
    {
        return $this->systemMessage;
    }

    /**
     * @param string $systemMessage
     * @return $this
     */
    public function setSystemMessage(string $systemMessage) : \lujie\dpd\soap\Type\LoginException
    {
        $this->systemMessage = $systemMessage;
        return $this;
    }

    /**
     * @param string $systemMessage
     * @return LoginException
     */
    public function withSystemMessage(string $systemMessage) : \lujie\dpd\soap\Type\LoginException
    {
        $new = clone $this;
        $new->systemMessage = $systemMessage;

        return $new;
    }

    /**
     * @return string
     */
    public function getSystemShortMessage()
    {
        return $this->systemShortMessage;
    }

    /**
     * @param string $systemShortMessage
     * @return $this
     */
    public function setSystemShortMessage(string $systemShortMessage) : \lujie\dpd\soap\Type\LoginException
    {
        $this->systemShortMessage = $systemShortMessage;
        return $this;
    }

    /**
     * @param string $systemShortMessage
     * @return LoginException
     */
    public function withSystemShortMessage(string $systemShortMessage) : \lujie\dpd\soap\Type\LoginException
    {
        $new = clone $this;
        $new->systemShortMessage = $systemShortMessage;

        return $new;
    }


}

