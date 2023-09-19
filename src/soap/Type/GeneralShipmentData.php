<?php

namespace lujie\dpd\soap\Type;

use yii\base\BaseObject;

class GeneralShipmentData extends BaseObject
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
     * @var \lujie\dpd\soap\Type\AddressWithType
     */
    private $sender;

    /**
     * @var \lujie\dpd\soap\Type\AddressWithType
     */
    private $recipient;

    /**
     * @var \lujie\dpd\soap\Type\AddressWithType
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
     * @param string $mpsId
     * @return $this
     */
    public function setMpsId(string $mpsId) : \lujie\dpd\soap\Type\GeneralShipmentData
    {
        $this->mpsId = $mpsId;
        return $this;
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
     * @param string $cUser
     * @return $this
     */
    public function setCUser(string $cUser) : \lujie\dpd\soap\Type\GeneralShipmentData
    {
        $this->cUser = $cUser;
        return $this;
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
     * @param string $mpsCustomerReferenceNumber1
     * @return $this
     */
    public function setMpsCustomerReferenceNumber1(string $mpsCustomerReferenceNumber1) : \lujie\dpd\soap\Type\GeneralShipmentData
    {
        $this->mpsCustomerReferenceNumber1 = $mpsCustomerReferenceNumber1;
        return $this;
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
     * @param string $mpsCustomerReferenceNumber2
     * @return $this
     */
    public function setMpsCustomerReferenceNumber2(string $mpsCustomerReferenceNumber2) : \lujie\dpd\soap\Type\GeneralShipmentData
    {
        $this->mpsCustomerReferenceNumber2 = $mpsCustomerReferenceNumber2;
        return $this;
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
     * @param string $mpsCustomerReferenceNumber3
     * @return $this
     */
    public function setMpsCustomerReferenceNumber3(string $mpsCustomerReferenceNumber3) : \lujie\dpd\soap\Type\GeneralShipmentData
    {
        $this->mpsCustomerReferenceNumber3 = $mpsCustomerReferenceNumber3;
        return $this;
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
     * @param string $mpsCustomerReferenceNumber4
     * @return $this
     */
    public function setMpsCustomerReferenceNumber4(string $mpsCustomerReferenceNumber4) : \lujie\dpd\soap\Type\GeneralShipmentData
    {
        $this->mpsCustomerReferenceNumber4 = $mpsCustomerReferenceNumber4;
        return $this;
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
     * @param string $identificationNumber
     * @return $this
     */
    public function setIdentificationNumber(string $identificationNumber) : \lujie\dpd\soap\Type\GeneralShipmentData
    {
        $this->identificationNumber = $identificationNumber;
        return $this;
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
     * @param string $sendingDepot
     * @return $this
     */
    public function setSendingDepot(string $sendingDepot) : \lujie\dpd\soap\Type\GeneralShipmentData
    {
        $this->sendingDepot = $sendingDepot;
        return $this;
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
     * @param string $product
     * @return $this
     */
    public function setProduct(string $product) : \lujie\dpd\soap\Type\GeneralShipmentData
    {
        $this->product = $product;
        return $this;
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
     * @param bool $mpsCompleteDelivery
     * @return $this
     */
    public function setMpsCompleteDelivery(bool $mpsCompleteDelivery) : \lujie\dpd\soap\Type\GeneralShipmentData
    {
        $this->mpsCompleteDelivery = $mpsCompleteDelivery;
        return $this;
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
     * @param bool $mpsCompleteDeliveryLabel
     * @return $this
     */
    public function setMpsCompleteDeliveryLabel(bool $mpsCompleteDeliveryLabel) : \lujie\dpd\soap\Type\GeneralShipmentData
    {
        $this->mpsCompleteDeliveryLabel = $mpsCompleteDeliveryLabel;
        return $this;
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
     * @param int $mpsVolume
     * @return $this
     */
    public function setMpsVolume(int $mpsVolume) : \lujie\dpd\soap\Type\GeneralShipmentData
    {
        $this->mpsVolume = $mpsVolume;
        return $this;
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
     * @param int $mpsWeight
     * @return $this
     */
    public function setMpsWeight(int $mpsWeight) : \lujie\dpd\soap\Type\GeneralShipmentData
    {
        $this->mpsWeight = $mpsWeight;
        return $this;
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
     * @param string $mpsExpectedSendingDate
     * @return $this
     */
    public function setMpsExpectedSendingDate(string $mpsExpectedSendingDate) : \lujie\dpd\soap\Type\GeneralShipmentData
    {
        $this->mpsExpectedSendingDate = $mpsExpectedSendingDate;
        return $this;
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
     * @param string $mpsExpectedSendingTime
     * @return $this
     */
    public function setMpsExpectedSendingTime(string $mpsExpectedSendingTime) : \lujie\dpd\soap\Type\GeneralShipmentData
    {
        $this->mpsExpectedSendingTime = $mpsExpectedSendingTime;
        return $this;
    }

    /**
     * @return \lujie\dpd\soap\Type\AddressWithType
     */
    public function getSender()
    {
        return $this->sender;
    }

    /**
     * @param \lujie\dpd\soap\Type\AddressWithType $sender
     * @return GeneralShipmentData
     */
    public function withSender($sender)
    {
        $new = clone $this;
        $new->sender = $sender;

        return $new;
    }

    /**
     * @param \lujie\dpd\soap\Type\AddressWithType $sender
     * @return $this
     */
    public function setSender($sender) : \lujie\dpd\soap\Type\GeneralShipmentData
    {
        $this->sender = $sender;
        return $this;
    }

    /**
     * @return \lujie\dpd\soap\Type\AddressWithType
     */
    public function getRecipient()
    {
        return $this->recipient;
    }

    /**
     * @param \lujie\dpd\soap\Type\AddressWithType $recipient
     * @return GeneralShipmentData
     */
    public function withRecipient($recipient)
    {
        $new = clone $this;
        $new->recipient = $recipient;

        return $new;
    }

    /**
     * @param \lujie\dpd\soap\Type\AddressWithType $recipient
     * @return $this
     */
    public function setRecipient($recipient) : \lujie\dpd\soap\Type\GeneralShipmentData
    {
        $this->recipient = $recipient;
        return $this;
    }

    /**
     * @return \lujie\dpd\soap\Type\AddressWithType
     */
    public function getReturnAddress()
    {
        return $this->returnAddress;
    }

    /**
     * @param \lujie\dpd\soap\Type\AddressWithType $returnAddress
     * @return GeneralShipmentData
     */
    public function withReturnAddress($returnAddress)
    {
        $new = clone $this;
        $new->returnAddress = $returnAddress;

        return $new;
    }

    /**
     * @param \lujie\dpd\soap\Type\AddressWithType $returnAddress
     * @return $this
     */
    public function setReturnAddress($returnAddress) : \lujie\dpd\soap\Type\GeneralShipmentData
    {
        $this->returnAddress = $returnAddress;
        return $this;
    }
}

