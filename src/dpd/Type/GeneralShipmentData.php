<?php

namespace dpd\Type;

class GeneralShipmentData
{

    /**
     * @var string
     */
    private $mpsId;

    /**
     * @var string
     */
    private $cUser;

    /**
     * @var string
     */
    private $mpsCustomerReferenceNumber1;

    /**
     * @var string
     */
    private $mpsCustomerReferenceNumber2;

    /**
     * @var string
     */
    private $mpsCustomerReferenceNumber3;

    /**
     * @var string
     */
    private $mpsCustomerReferenceNumber4;

    /**
     * @var string
     */
    private $identificationNumber;

    /**
     * @var string
     */
    private $sendingDepot;

    /**
     * @var string
     */
    private $product;

    /**
     * @var bool
     */
    private $mpsCompleteDelivery;

    /**
     * @var bool
     */
    private $mpsCompleteDeliveryLabel;

    /**
     * @var int
     */
    private $mpsVolume;

    /**
     * @var int
     */
    private $mpsWeight;

    /**
     * @var string
     */
    private $mpsExpectedSendingDate;

    /**
     * @var string
     */
    private $mpsExpectedSendingTime;

    /**
     * @var \dpd\Type\AddressWithType
     */
    private $sender;

    /**
     * @var \dpd\Type\AddressWithType
     */
    private $recipient;

    /**
     * @var \dpd\Type\AddressWithType
     */
    private $returnAddress;

    /**
     * @return string
     */
    public function getMpsId()
    {
        return $this->mpsId;
    }

    /**
     * @param string $mpsId
     * @return GeneralShipmentData
     */
    public function withMpsId($mpsId)
    {
        $new = clone $this;
        $new->mpsId = $mpsId;

        return $new;
    }

    /**
     * @return string
     */
    public function getCUser()
    {
        return $this->cUser;
    }

    /**
     * @param string $cUser
     * @return GeneralShipmentData
     */
    public function withCUser($cUser)
    {
        $new = clone $this;
        $new->cUser = $cUser;

        return $new;
    }

    /**
     * @return string
     */
    public function getMpsCustomerReferenceNumber1()
    {
        return $this->mpsCustomerReferenceNumber1;
    }

    /**
     * @param string $mpsCustomerReferenceNumber1
     * @return GeneralShipmentData
     */
    public function withMpsCustomerReferenceNumber1($mpsCustomerReferenceNumber1)
    {
        $new = clone $this;
        $new->mpsCustomerReferenceNumber1 = $mpsCustomerReferenceNumber1;

        return $new;
    }

    /**
     * @return string
     */
    public function getMpsCustomerReferenceNumber2()
    {
        return $this->mpsCustomerReferenceNumber2;
    }

    /**
     * @param string $mpsCustomerReferenceNumber2
     * @return GeneralShipmentData
     */
    public function withMpsCustomerReferenceNumber2($mpsCustomerReferenceNumber2)
    {
        $new = clone $this;
        $new->mpsCustomerReferenceNumber2 = $mpsCustomerReferenceNumber2;

        return $new;
    }

    /**
     * @return string
     */
    public function getMpsCustomerReferenceNumber3()
    {
        return $this->mpsCustomerReferenceNumber3;
    }

    /**
     * @param string $mpsCustomerReferenceNumber3
     * @return GeneralShipmentData
     */
    public function withMpsCustomerReferenceNumber3($mpsCustomerReferenceNumber3)
    {
        $new = clone $this;
        $new->mpsCustomerReferenceNumber3 = $mpsCustomerReferenceNumber3;

        return $new;
    }

    /**
     * @return string
     */
    public function getMpsCustomerReferenceNumber4()
    {
        return $this->mpsCustomerReferenceNumber4;
    }

    /**
     * @param string $mpsCustomerReferenceNumber4
     * @return GeneralShipmentData
     */
    public function withMpsCustomerReferenceNumber4($mpsCustomerReferenceNumber4)
    {
        $new = clone $this;
        $new->mpsCustomerReferenceNumber4 = $mpsCustomerReferenceNumber4;

        return $new;
    }

    /**
     * @return string
     */
    public function getIdentificationNumber()
    {
        return $this->identificationNumber;
    }

    /**
     * @param string $identificationNumber
     * @return GeneralShipmentData
     */
    public function withIdentificationNumber($identificationNumber)
    {
        $new = clone $this;
        $new->identificationNumber = $identificationNumber;

        return $new;
    }

    /**
     * @return string
     */
    public function getSendingDepot()
    {
        return $this->sendingDepot;
    }

    /**
     * @param string $sendingDepot
     * @return GeneralShipmentData
     */
    public function withSendingDepot($sendingDepot)
    {
        $new = clone $this;
        $new->sendingDepot = $sendingDepot;

        return $new;
    }

    /**
     * @return string
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * @param string $product
     * @return GeneralShipmentData
     */
    public function withProduct($product)
    {
        $new = clone $this;
        $new->product = $product;

        return $new;
    }

    /**
     * @return bool
     */
    public function getMpsCompleteDelivery()
    {
        return $this->mpsCompleteDelivery;
    }

    /**
     * @param bool $mpsCompleteDelivery
     * @return GeneralShipmentData
     */
    public function withMpsCompleteDelivery($mpsCompleteDelivery)
    {
        $new = clone $this;
        $new->mpsCompleteDelivery = $mpsCompleteDelivery;

        return $new;
    }

    /**
     * @return bool
     */
    public function getMpsCompleteDeliveryLabel()
    {
        return $this->mpsCompleteDeliveryLabel;
    }

    /**
     * @param bool $mpsCompleteDeliveryLabel
     * @return GeneralShipmentData
     */
    public function withMpsCompleteDeliveryLabel($mpsCompleteDeliveryLabel)
    {
        $new = clone $this;
        $new->mpsCompleteDeliveryLabel = $mpsCompleteDeliveryLabel;

        return $new;
    }

    /**
     * @return int
     */
    public function getMpsVolume()
    {
        return $this->mpsVolume;
    }

    /**
     * @param int $mpsVolume
     * @return GeneralShipmentData
     */
    public function withMpsVolume($mpsVolume)
    {
        $new = clone $this;
        $new->mpsVolume = $mpsVolume;

        return $new;
    }

    /**
     * @return int
     */
    public function getMpsWeight()
    {
        return $this->mpsWeight;
    }

    /**
     * @param int $mpsWeight
     * @return GeneralShipmentData
     */
    public function withMpsWeight($mpsWeight)
    {
        $new = clone $this;
        $new->mpsWeight = $mpsWeight;

        return $new;
    }

    /**
     * @return string
     */
    public function getMpsExpectedSendingDate()
    {
        return $this->mpsExpectedSendingDate;
    }

    /**
     * @param string $mpsExpectedSendingDate
     * @return GeneralShipmentData
     */
    public function withMpsExpectedSendingDate($mpsExpectedSendingDate)
    {
        $new = clone $this;
        $new->mpsExpectedSendingDate = $mpsExpectedSendingDate;

        return $new;
    }

    /**
     * @return string
     */
    public function getMpsExpectedSendingTime()
    {
        return $this->mpsExpectedSendingTime;
    }

    /**
     * @param string $mpsExpectedSendingTime
     * @return GeneralShipmentData
     */
    public function withMpsExpectedSendingTime($mpsExpectedSendingTime)
    {
        $new = clone $this;
        $new->mpsExpectedSendingTime = $mpsExpectedSendingTime;

        return $new;
    }

    /**
     * @return \dpd\Type\AddressWithType
     */
    public function getSender()
    {
        return $this->sender;
    }

    /**
     * @param \dpd\Type\AddressWithType $sender
     * @return GeneralShipmentData
     */
    public function withSender($sender)
    {
        $new = clone $this;
        $new->sender = $sender;

        return $new;
    }

    /**
     * @return \dpd\Type\AddressWithType
     */
    public function getRecipient()
    {
        return $this->recipient;
    }

    /**
     * @param \dpd\Type\AddressWithType $recipient
     * @return GeneralShipmentData
     */
    public function withRecipient($recipient)
    {
        $new = clone $this;
        $new->recipient = $recipient;

        return $new;
    }

    /**
     * @return \dpd\Type\AddressWithType
     */
    public function getReturnAddress()
    {
        return $this->returnAddress;
    }

    /**
     * @param \dpd\Type\AddressWithType $returnAddress
     * @return GeneralShipmentData
     */
    public function withReturnAddress($returnAddress)
    {
        $new = clone $this;
        $new->returnAddress = $returnAddress;

        return $new;
    }


}

