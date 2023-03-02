<?php

namespace lujie\dpd\soap\Type;

use yii\base\BaseObject;

class ShipmentResponse extends BaseObject
{

    /**
     * @var string
     */
    private $identificationNumber;

    /**
     * @var string
     */
    private $mpsId;

    /**
     * @var \lujie\dpd\soap\Type\ParcelInformationType
     */
    private $parcelInformation;

    /**
     * @var \lujie\dpd\soap\Type\FaultCodeType
     */
    private $faults;

    /**
     * @return string
     */
    public function getIdentificationNumber()
    {
        return $this->identificationNumber;
    }

    /**
     * @param string $identificationNumber
     * @return $this
     */
    public function setIdentificationNumber(string $identificationNumber) : \lujie\dpd\soap\Type\ShipmentResponse
    {
        $this->identificationNumber = $identificationNumber;
        return $this;
    }

    /**
     * @param string $identificationNumber
     * @return ShipmentResponse
     */
    public function withIdentificationNumber(string $identificationNumber) : \lujie\dpd\soap\Type\ShipmentResponse
    {
        $new = clone $this;
        $new->identificationNumber = $identificationNumber;

        return $new;
    }

    /**
     * @return string
     */
    public function getMpsId()
    {
        return $this->mpsId;
    }

    /**
     * @param string $mpsId
     * @return $this
     */
    public function setMpsId(string $mpsId) : \lujie\dpd\soap\Type\ShipmentResponse
    {
        $this->mpsId = $mpsId;
        return $this;
    }

    /**
     * @param string $mpsId
     * @return ShipmentResponse
     */
    public function withMpsId(string $mpsId) : \lujie\dpd\soap\Type\ShipmentResponse
    {
        $new = clone $this;
        $new->mpsId = $mpsId;

        return $new;
    }

    /**
     * @return \lujie\dpd\soap\Type\ParcelInformationType
     */
    public function getParcelInformation()
    {
        return $this->parcelInformation;
    }

    /**
     * @param \lujie\dpd\soap\Type\ParcelInformationType $parcelInformation
     * @return $this
     */
    public function setParcelInformation($parcelInformation) : \lujie\dpd\soap\Type\ShipmentResponse
    {
        $this->parcelInformation = $parcelInformation;
        return $this;
    }

    /**
     * @param \lujie\dpd\soap\Type\ParcelInformationType $parcelInformation
     * @return ShipmentResponse
     */
    public function withParcelInformation(\lujie\dpd\soap\Type\ParcelInformationType $parcelInformation) : \lujie\dpd\soap\Type\ShipmentResponse
    {
        $new = clone $this;
        $new->parcelInformation = $parcelInformation;

        return $new;
    }

    /**
     * @return \lujie\dpd\soap\Type\FaultCodeType
     */
    public function getFaults()
    {
        return $this->faults;
    }

    /**
     * @param \lujie\dpd\soap\Type\FaultCodeType $faults
     * @return $this
     */
    public function setFaults($faults) : \lujie\dpd\soap\Type\ShipmentResponse
    {
        $this->faults = $faults;
        return $this;
    }

    /**
     * @param \lujie\dpd\soap\Type\FaultCodeType $faults
     * @return ShipmentResponse
     */
    public function withFaults(\lujie\dpd\soap\Type\FaultCodeType $faults) : \lujie\dpd\soap\Type\ShipmentResponse
    {
        $new = clone $this;
        $new->faults = $faults;

        return $new;
    }


}

