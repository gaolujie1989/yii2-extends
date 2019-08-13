<?php

namespace dpd\Type;

use Phpro\SoapClient\Type\ResultInterface;

class ShipmentResponse implements ResultInterface
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
     * @var \dpd\Type\ParcelInformationType
     */
    private $parcelInformation;

    /**
     * @var \dpd\Type\FaultCodeType
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
     * @return ShipmentResponse
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
    public function getMpsId()
    {
        return $this->mpsId;
    }

    /**
     * @param string $mpsId
     * @return ShipmentResponse
     */
    public function withMpsId($mpsId)
    {
        $new = clone $this;
        $new->mpsId = $mpsId;

        return $new;
    }

    /**
     * @return \dpd\Type\ParcelInformationType
     */
    public function getParcelInformation()
    {
        return $this->parcelInformation;
    }

    /**
     * @param \dpd\Type\ParcelInformationType $parcelInformation
     * @return ShipmentResponse
     */
    public function withParcelInformation($parcelInformation)
    {
        $new = clone $this;
        $new->parcelInformation = $parcelInformation;

        return $new;
    }

    /**
     * @return \dpd\Type\FaultCodeType
     */
    public function getFaults()
    {
        return $this->faults;
    }

    /**
     * @param \dpd\Type\FaultCodeType $faults
     * @return ShipmentResponse
     */
    public function withFaults($faults)
    {
        $new = clone $this;
        $new->faults = $faults;

        return $new;
    }


}

