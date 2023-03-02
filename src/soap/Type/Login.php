<?php

namespace lujie\dpd\soap\Type;

use yii\base\BaseObject;

class Login extends BaseObject
{

    /**
     * @var string
     */
    private $delisId;

    /**
     * @var string
     */
    private $customerUid;

    /**
     * @var string
     */
    private $authToken;

    /**
     * @var string
     */
    private $depot;

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
    public function setDelisId(string $delisId) : \lujie\dpd\soap\Type\Login
    {
        $this->delisId = $delisId;
        return $this;
    }

    /**
     * @param string $delisId
     * @return Login
     */
    public function withDelisId(string $delisId) : \lujie\dpd\soap\Type\Login
    {
        $new = clone $this;
        $new->delisId = $delisId;

        return $new;
    }

    /**
     * @return string
     */
    public function getCustomerUid()
    {
        return $this->customerUid;
    }

    /**
     * @param string $customerUid
     * @return $this
     */
    public function setCustomerUid(string $customerUid) : \lujie\dpd\soap\Type\Login
    {
        $this->customerUid = $customerUid;
        return $this;
    }

    /**
     * @param string $customerUid
     * @return Login
     */
    public function withCustomerUid(string $customerUid) : \lujie\dpd\soap\Type\Login
    {
        $new = clone $this;
        $new->customerUid = $customerUid;

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
     * @return $this
     */
    public function setAuthToken(string $authToken) : \lujie\dpd\soap\Type\Login
    {
        $this->authToken = $authToken;
        return $this;
    }

    /**
     * @param string $authToken
     * @return Login
     */
    public function withAuthToken(string $authToken) : \lujie\dpd\soap\Type\Login
    {
        $new = clone $this;
        $new->authToken = $authToken;

        return $new;
    }

    /**
     * @return string
     */
    public function getDepot()
    {
        return $this->depot;
    }

    /**
     * @param string $depot
     * @return $this
     */
    public function setDepot(string $depot) : \lujie\dpd\soap\Type\Login
    {
        $this->depot = $depot;
        return $this;
    }

    /**
     * @param string $depot
     * @return Login
     */
    public function withDepot(string $depot) : \lujie\dpd\soap\Type\Login
    {
        $new = clone $this;
        $new->depot = $depot;

        return $new;
    }


}

