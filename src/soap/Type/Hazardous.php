<?php

namespace lujie\dpd\soap\Type;

use yii\base\BaseObject;

class Hazardous extends BaseObject
{

    /**
     * @var string
     */
    private $identificationUnNo;

    /**
     * @var string
     */
    private $identificationClass;

    /**
     * @var string
     */
    private $classificationCode;

    /**
     * @var string
     */
    private $packingGroup;

    /**
     * @var string
     */
    private $packingCode;

    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $subsidiaryRisk;

    /**
     * @var string
     */
    private $tunnelRestrictionCode;

    /**
     * @var float
     */
    private $hazardousWeight;

    /**
     * @var float
     */
    private $netWeight;

    /**
     * @var int
     */
    private $factor;

    /**
     * @var string
     */
    private $notOtherwiseSpecified;

    /**
     * @return string
     */
    public function getIdentificationUnNo() : string
    {
        return $this->identificationUnNo;
    }

    /**
     * @param string $identificationUnNo
     * @return $this
     */
    public function setIdentificationUnNo(string $identificationUnNo) : \lujie\dpd\soap\Type\Hazardous
    {
        $this->identificationUnNo = $identificationUnNo;
        return $this;
    }

    /**
     * @param string $identificationUnNo
     * @return Hazardous
     */
    public function withIdentificationUnNo(string $identificationUnNo) : \lujie\dpd\soap\Type\Hazardous
    {
        $new = clone $this;
        $new->identificationUnNo = $identificationUnNo;

        return $new;
    }

    /**
     * @return string
     */
    public function getIdentificationClass() : string
    {
        return $this->identificationClass;
    }

    /**
     * @param string $identificationClass
     * @return $this
     */
    public function setIdentificationClass(string $identificationClass) : \lujie\dpd\soap\Type\Hazardous
    {
        $this->identificationClass = $identificationClass;
        return $this;
    }

    /**
     * @param string $identificationClass
     * @return Hazardous
     */
    public function withIdentificationClass(string $identificationClass) : \lujie\dpd\soap\Type\Hazardous
    {
        $new = clone $this;
        $new->identificationClass = $identificationClass;

        return $new;
    }

    /**
     * @return string
     */
    public function getClassificationCode() : string
    {
        return $this->classificationCode;
    }

    /**
     * @param string $classificationCode
     * @return $this
     */
    public function setClassificationCode(string $classificationCode) : \lujie\dpd\soap\Type\Hazardous
    {
        $this->classificationCode = $classificationCode;
        return $this;
    }

    /**
     * @param string $classificationCode
     * @return Hazardous
     */
    public function withClassificationCode(string $classificationCode) : \lujie\dpd\soap\Type\Hazardous
    {
        $new = clone $this;
        $new->classificationCode = $classificationCode;

        return $new;
    }

    /**
     * @return string
     */
    public function getPackingGroup() : string
    {
        return $this->packingGroup;
    }

    /**
     * @param string $packingGroup
     * @return $this
     */
    public function setPackingGroup(string $packingGroup) : \lujie\dpd\soap\Type\Hazardous
    {
        $this->packingGroup = $packingGroup;
        return $this;
    }

    /**
     * @param string $packingGroup
     * @return Hazardous
     */
    public function withPackingGroup(string $packingGroup) : \lujie\dpd\soap\Type\Hazardous
    {
        $new = clone $this;
        $new->packingGroup = $packingGroup;

        return $new;
    }

    /**
     * @return string
     */
    public function getPackingCode() : string
    {
        return $this->packingCode;
    }

    /**
     * @param string $packingCode
     * @return $this
     */
    public function setPackingCode(string $packingCode) : \lujie\dpd\soap\Type\Hazardous
    {
        $this->packingCode = $packingCode;
        return $this;
    }

    /**
     * @param string $packingCode
     * @return Hazardous
     */
    public function withPackingCode(string $packingCode) : \lujie\dpd\soap\Type\Hazardous
    {
        $new = clone $this;
        $new->packingCode = $packingCode;

        return $new;
    }

    /**
     * @return string
     */
    public function getDescription() : string
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return $this
     */
    public function setDescription(string $description) : \lujie\dpd\soap\Type\Hazardous
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @param string $description
     * @return Hazardous
     */
    public function withDescription(string $description) : \lujie\dpd\soap\Type\Hazardous
    {
        $new = clone $this;
        $new->description = $description;

        return $new;
    }

    /**
     * @return string
     */
    public function getSubsidiaryRisk() : string
    {
        return $this->subsidiaryRisk;
    }

    /**
     * @param string $subsidiaryRisk
     * @return $this
     */
    public function setSubsidiaryRisk(string $subsidiaryRisk) : \lujie\dpd\soap\Type\Hazardous
    {
        $this->subsidiaryRisk = $subsidiaryRisk;
        return $this;
    }

    /**
     * @param string $subsidiaryRisk
     * @return Hazardous
     */
    public function withSubsidiaryRisk(string $subsidiaryRisk) : \lujie\dpd\soap\Type\Hazardous
    {
        $new = clone $this;
        $new->subsidiaryRisk = $subsidiaryRisk;

        return $new;
    }

    /**
     * @return string
     */
    public function getTunnelRestrictionCode() : string
    {
        return $this->tunnelRestrictionCode;
    }

    /**
     * @param string $tunnelRestrictionCode
     * @return $this
     */
    public function setTunnelRestrictionCode(string $tunnelRestrictionCode) : \lujie\dpd\soap\Type\Hazardous
    {
        $this->tunnelRestrictionCode = $tunnelRestrictionCode;
        return $this;
    }

    /**
     * @param string $tunnelRestrictionCode
     * @return Hazardous
     */
    public function withTunnelRestrictionCode(string $tunnelRestrictionCode) : \lujie\dpd\soap\Type\Hazardous
    {
        $new = clone $this;
        $new->tunnelRestrictionCode = $tunnelRestrictionCode;

        return $new;
    }

    /**
     * @return float
     */
    public function getHazardousWeight() : float
    {
        return $this->hazardousWeight;
    }

    /**
     * @param float $hazardousWeight
     * @return $this
     */
    public function setHazardousWeight(float $hazardousWeight) : \lujie\dpd\soap\Type\Hazardous
    {
        $this->hazardousWeight = $hazardousWeight;
        return $this;
    }

    /**
     * @param float $hazardousWeight
     * @return Hazardous
     */
    public function withHazardousWeight(float $hazardousWeight) : \lujie\dpd\soap\Type\Hazardous
    {
        $new = clone $this;
        $new->hazardousWeight = $hazardousWeight;

        return $new;
    }

    /**
     * @return float
     */
    public function getNetWeight() : float
    {
        return $this->netWeight;
    }

    /**
     * @param float $netWeight
     * @return $this
     */
    public function setNetWeight(float $netWeight) : \lujie\dpd\soap\Type\Hazardous
    {
        $this->netWeight = $netWeight;
        return $this;
    }

    /**
     * @param float $netWeight
     * @return Hazardous
     */
    public function withNetWeight(float $netWeight) : \lujie\dpd\soap\Type\Hazardous
    {
        $new = clone $this;
        $new->netWeight = $netWeight;

        return $new;
    }

    /**
     * @return int
     */
    public function getFactor() : int
    {
        return $this->factor;
    }

    /**
     * @param int $factor
     * @return $this
     */
    public function setFactor(int $factor) : \lujie\dpd\soap\Type\Hazardous
    {
        $this->factor = $factor;
        return $this;
    }

    /**
     * @param int $factor
     * @return Hazardous
     */
    public function withFactor(int $factor) : \lujie\dpd\soap\Type\Hazardous
    {
        $new = clone $this;
        $new->factor = $factor;

        return $new;
    }

    /**
     * @return string
     */
    public function getNotOtherwiseSpecified() : string
    {
        return $this->notOtherwiseSpecified;
    }

    /**
     * @param string $notOtherwiseSpecified
     * @return $this
     */
    public function setNotOtherwiseSpecified(string $notOtherwiseSpecified) : \lujie\dpd\soap\Type\Hazardous
    {
        $this->notOtherwiseSpecified = $notOtherwiseSpecified;
        return $this;
    }

    /**
     * @param string $notOtherwiseSpecified
     * @return Hazardous
     */
    public function withNotOtherwiseSpecified(string $notOtherwiseSpecified) : \lujie\dpd\soap\Type\Hazardous
    {
        $new = clone $this;
        $new->notOtherwiseSpecified = $notOtherwiseSpecified;

        return $new;
    }


}

