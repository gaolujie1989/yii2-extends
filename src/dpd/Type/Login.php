<?php

namespace dpd\Type;

use Phpro\SoapClient\Type\RequestInterface;

class Login implements RequestInterface
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
     * Constructor
     *
     * @var string $delisId
     * @var string $customerUid
     * @var string $authToken
     * @var string $depot
     */
    public function __construct($delisId, $customerUid, $authToken, $depot)
    {
        $this->delisId = $delisId;
        $this->customerUid = $customerUid;
        $this->authToken = $authToken;
        $this->depot = $depot;
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

