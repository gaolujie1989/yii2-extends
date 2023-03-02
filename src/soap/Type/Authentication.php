<?php

namespace lujie\dpd\soap\Type;


use yii\base\BaseObject;

class Authentication extends BaseObject
{

    /**
     * @var string
     */
    private $delisId;

    /**
     * @var string
     */
    private $authToken;

    /**
     * @var string
     */
    private $messageLanguage;

    /**
     * @return string
     */
    public function getDelisId() : string
    {
        return $this->delisId;
    }

    /**
     * @param string $delisId
     * @return $this
     */
    public function setDelisId(string $delisId) : \lujie\dpd\soap\Type\Authentication
    {
        $this->delisId = $delisId;
        return $this;
    }

    /**
     * @param string $delisId
     * @return Authentication
     */
    public function withDelisId(string $delisId) : \lujie\dpd\soap\Type\Authentication
    {
        $new = clone $this;
        $new->delisId = $delisId;

        return $new;
    }

    /**
     * @return string
     */
    public function getAuthToken() : string
    {
        return $this->authToken;
    }

    /**
     * @param string $authToken
     * @return $this
     */
    public function setAuthToken(string $authToken) : \lujie\dpd\soap\Type\Authentication
    {
        $this->authToken = $authToken;
        return $this;
    }

    /**
     * @param string $authToken
     * @return Authentication
     */
    public function withAuthToken(string $authToken) : \lujie\dpd\soap\Type\Authentication
    {
        $new = clone $this;
        $new->authToken = $authToken;

        return $new;
    }

    /**
     * @return string
     */
    public function getMessageLanguage() : string
    {
        return $this->messageLanguage;
    }

    /**
     * @param string $messageLanguage
     * @return $this
     */
    public function setMessageLanguage(string $messageLanguage) : \lujie\dpd\soap\Type\Authentication
    {
        $this->messageLanguage = $messageLanguage;
        return $this;
    }

    /**
     * @param string $messageLanguage
     * @return Authentication
     */
    public function withMessageLanguage(string $messageLanguage) : \lujie\dpd\soap\Type\Authentication
    {
        $new = clone $this;
        $new->messageLanguage = $messageLanguage;

        return $new;
    }


}

