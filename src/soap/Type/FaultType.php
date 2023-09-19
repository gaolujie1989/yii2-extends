<?php

namespace lujie\dpd\soap\Type;

use yii\base\BaseObject;

class FaultType extends BaseObject
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
     * @return FaultType
     */
    public function withAdditionalData($additionalData)
    {
        $new = clone $this;
        $new->additionalData = $additionalData;

        return $new;
    }

    /**
     * @param string $additionalData
     * @return $this
     */
    public function setAdditionalData(string $additionalData) : \lujie\dpd\soap\Type\FaultType
    {
        $this->additionalData = $additionalData;
        return $this;
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
     * @return FaultType
     */
    public function withAdditionalInfo($additionalInfo)
    {
        $new = clone $this;
        $new->additionalInfo = $additionalInfo;

        return $new;
    }

    /**
     * @param string $additionalInfo
     * @return $this
     */
    public function setAdditionalInfo(string $additionalInfo) : \lujie\dpd\soap\Type\FaultType
    {
        $this->additionalInfo = $additionalInfo;
        return $this;
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
     * @return FaultType
     */
    public function withErrorClass($errorClass)
    {
        $new = clone $this;
        $new->errorClass = $errorClass;

        return $new;
    }

    /**
     * @param string $errorClass
     * @return $this
     */
    public function setErrorClass(string $errorClass) : \lujie\dpd\soap\Type\FaultType
    {
        $this->errorClass = $errorClass;
        return $this;
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
     * @return FaultType
     */
    public function withErrorCode($errorCode)
    {
        $new = clone $this;
        $new->errorCode = $errorCode;

        return $new;
    }

    /**
     * @param string $errorCode
     * @return $this
     */
    public function setErrorCode(string $errorCode) : \lujie\dpd\soap\Type\FaultType
    {
        $this->errorCode = $errorCode;
        return $this;
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
     * @return FaultType
     */
    public function withFullMessage($fullMessage)
    {
        $new = clone $this;
        $new->fullMessage = $fullMessage;

        return $new;
    }

    /**
     * @param string $fullMessage
     * @return $this
     */
    public function setFullMessage(string $fullMessage) : \lujie\dpd\soap\Type\FaultType
    {
        $this->fullMessage = $fullMessage;
        return $this;
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
     * @return FaultType
     */
    public function withLanguage($language)
    {
        $new = clone $this;
        $new->language = $language;

        return $new;
    }

    /**
     * @param string $language
     * @return $this
     */
    public function setLanguage(string $language) : \lujie\dpd\soap\Type\FaultType
    {
        $this->language = $language;
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
     * @return FaultType
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
    public function setMessage(string $message) : \lujie\dpd\soap\Type\FaultType
    {
        $this->message = $message;
        return $this;
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
     * @return FaultType
     */
    public function withShortMessage($shortMessage)
    {
        $new = clone $this;
        $new->shortMessage = $shortMessage;

        return $new;
    }

    /**
     * @param string $shortMessage
     * @return $this
     */
    public function setShortMessage(string $shortMessage) : \lujie\dpd\soap\Type\FaultType
    {
        $this->shortMessage = $shortMessage;
        return $this;
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
     * @return FaultType
     */
    public function withSystemFullMessage($systemFullMessage)
    {
        $new = clone $this;
        $new->systemFullMessage = $systemFullMessage;

        return $new;
    }

    /**
     * @param string $systemFullMessage
     * @return $this
     */
    public function setSystemFullMessage(string $systemFullMessage) : \lujie\dpd\soap\Type\FaultType
    {
        $this->systemFullMessage = $systemFullMessage;
        return $this;
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
     * @return FaultType
     */
    public function withSystemMessage($systemMessage)
    {
        $new = clone $this;
        $new->systemMessage = $systemMessage;

        return $new;
    }

    /**
     * @param string $systemMessage
     * @return $this
     */
    public function setSystemMessage(string $systemMessage) : \lujie\dpd\soap\Type\FaultType
    {
        $this->systemMessage = $systemMessage;
        return $this;
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
     * @return FaultType
     */
    public function withSystemShortMessage($systemShortMessage)
    {
        $new = clone $this;
        $new->systemShortMessage = $systemShortMessage;

        return $new;
    }

    /**
     * @param string $systemShortMessage
     * @return $this
     */
    public function setSystemShortMessage(string $systemShortMessage) : \lujie\dpd\soap\Type\FaultType
    {
        $this->systemShortMessage = $systemShortMessage;
        return $this;
    }
}

