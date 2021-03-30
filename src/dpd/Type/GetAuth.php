<?php

namespace dpd\Type;

use Phpro\SoapClient\Type\RequestInterface;

class GetAuth implements RequestInterface
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
     * Constructor
     *
     * @var string $delisId
     * @var string $password
     * @var string $messageLanguage
     */
    public function __construct($delisId, $password, $messageLanguage)
    {
        $this->delisId = $delisId;
        $this->password = $password;
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
     * @return GetAuth
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
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     * @return GetAuth
     */
    public function withPassword($password)
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
     * @return GetAuth
     */
    public function withMessageLanguage($messageLanguage)
    {
        $new = clone $this;
        $new->messageLanguage = $messageLanguage;

        return $new;
    }
}
