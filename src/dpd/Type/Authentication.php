<?php

namespace dpd\Type;

class Authentication
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

