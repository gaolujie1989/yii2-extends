<?php

namespace dpd\Type;

class Login
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
     * @return Login
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
    public function getCustomerUid()
    {
        return $this->customerUid;
    }

    /**
     * @param string $customerUid
     * @return Login
     */
    public function withCustomerUid($customerUid)
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
     * @return Login
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
    public function getDepot()
    {
        return $this->depot;
    }

    /**
     * @param string $depot
     * @return Login
     */
    public function withDepot($depot)
    {
        $new = clone $this;
        $new->depot = $depot;

        return $new;
    }


}

