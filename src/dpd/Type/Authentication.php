<?php

namespace dpd\Type;


use Phpro\SoapClient\Type\RequestInterface;

class Authentication implements RequestInterface
{

    /**
     * @var string
     */
    private $delisId = null;

    /**
     * @var string
     */
    private $authToken = null;

    /**
     * @var string
     */
    private $messageLanguage = null;

    /**
     * Constructor
     *
     * @var string $delisId
     * @var string $authToken
     * @var string $messageLanguage
     */
    public function __construct($delisId, $authToken, $messageLanguage)
    {
        $this->delisId = $delisId;
        $this->authToken = $authToken;
        $this->messageLanguage = $messageLanguage;
    }

    /**
     * @return string
     */
    public function getDelisId()
    {
        return $this->delisId;
    }

    /**
     * @param string $delisId
     * @return Authentication
     */
    public function withDelisId($delisId)
    {
        $new = clone $this;
        $new->delisId = $delisId;

        return $new;
    }

    /**
     * @return string
     */
    public function getAuthToken()
    {
        return $this->authToken;
    }

    /**
     * @param string $authToken
     * @return Authentication
     */
    public function withAuthToken($authToken)
    {
        $new = clone $this;
        $new->authToken = $authToken;

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
     * @return Authentication
     */
    public function withMessageLanguage($messageLanguage)
    {
        $new = clone $this;
        $new->messageLanguage = $messageLanguage;

        return $new;
    }


}

