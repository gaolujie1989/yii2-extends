<?php

namespace lujie\dpd\soap\Type;

use yii\base\BaseObject;
use Phpro\SoapClient\Type\RequestInterface;

class GetAuth extends BaseObject implements RequestInterface
{

    /**
     * @var string
     */
    private $delisId;

    /**
     * @var string
     */
    private $password;

    /**
     * @var string
     */
    private $messageLanguage;

    /**
     * @return string
     */
    public function getDelisId()
    {
        return $this->delisId;
    }

    /**
     * @param string $delisId
     * @return $this
     */
    public function setDelisId(string $delisId) : \lujie\dpd\soap\Type\GetAuth
    {
        $this->delisId = $delisId;
        return $this;
    }

    /**
     * @param string $delisId
     * @return GetAuth
     */
    public function withDelisId(string $delisId) : \lujie\dpd\soap\Type\GetAuth
    {
        $new = clone $this;
        $new->delisId = $delisId;

        return $new;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     * @return $this
     */
    public function setPassword(string $password) : \lujie\dpd\soap\Type\GetAuth
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @param string $password
     * @return GetAuth
     */
    public function withPassword(string $password) : \lujie\dpd\soap\Type\GetAuth
    {
        $new = clone $this;
        $new->password = $password;

        return $new;
    }

    /**
     * @return string
     */
    public function getMessageLanguage()
    {
        return $this->messageLanguage;
    }

    /**
     * @param string $messageLanguage
     * @return $this
     */
    public function setMessageLanguage(string $messageLanguage) : \lujie\dpd\soap\Type\GetAuth
    {
        $this->messageLanguage = $messageLanguage;
        return $this;
    }

    /**
     * @param string $messageLanguage
     * @return GetAuth
     */
    public function withMessageLanguage(string $messageLanguage) : \lujie\dpd\soap\Type\GetAuth
    {
        $new = clone $this;
        $new->messageLanguage = $messageLanguage;

        return $new;
    }


}

