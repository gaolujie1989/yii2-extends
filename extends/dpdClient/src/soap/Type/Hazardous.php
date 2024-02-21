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
    public function getIdentificationUnNo()
    {
        return $this->identificationUnNo;
    }

    /**
     * @param string $identificationUnNo
     * @return Hazardous
     */
    public function withIdentificationUnNo($identificationUnNo)
    {
        $new = clone $this;
        $new->identificationUnNo = $identificationUnNo;

        return $new;
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
     * @return string
     */
    public function getIdentificationClass()
    {
        return $this->identificationClass;
    }

    /**
     * @param string $identificationClass
     * @return Hazardous
     */
    public function withIdentificationClass($identificationClass)
    {
        $new = clone $this;
        $new->identificationClass = $identificationClass;

        return $new;
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
     * @return string
     */
    public function getClassificationCode()
    {
        return $this->classificationCode;
    }

    /**
     * @param string $classificationCode
     * @return Hazardous
     */
    public function withClassificationCode($classificationCode)
    {
        $new = clone $this;
        $new->classificationCode = $classificationCode;

        return $new;
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
     * @return string
     */
    public function getPackingGroup()
    {
        return $this->packingGroup;
    }

    /**
     * @param string $packingGroup
     * @return Hazardous
     */
    public function withPackingGroup($packingGroup)
    {
        $new = clone $this;
        $new->packingGroup = $packingGroup;

        return $new;
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
     * @return string
     */
    public function getPackingCode()
    {
        return $this->packingCode;
    }

    /**
     * @param string $packingCode
     * @return Hazardous
     */
    public function withPackingCode($packingCode)
    {
        $new = clone $this;
        $new->packingCode = $packingCode;

        return $new;
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
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return Hazardous
     */
    public function withDescription($description)
    {
        $new = clone $this;
        $new->description = $description;

        return $new;
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
     * @return string
     */
    public function getSubsidiaryRisk()
    {
        return $this->subsidiaryRisk;
    }

    /**
     * @param string $subsidiaryRisk
     * @return Hazardous
     */
    public function withSubsidiaryRisk($subsidiaryRisk)
    {
        $new = clone $this;
        $new->subsidiaryRisk = $subsidiaryRisk;

        return $new;
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
     * @return string
     */
    public function getTunnelRestrictionCode()
    {
        return $this->tunnelRestrictionCode;
    }

    /**
     * @param string $tunnelRestrictionCode
     * @return Hazardous
     */
    public function withTunnelRestrictionCode($tunnelRestrictionCode)
    {
        $new = clone $this;
        $new->tunnelRestrictionCode = $tunnelRestrictionCode;

        return $new;
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
     * @return float
     */
    public function getHazardousWeight()
    {
        return $this->hazardousWeight;
    }

    /**
     * @param float $hazardousWeight
     * @return Hazardous
     */
    public function withHazardousWeight($hazardousWeight)
    {
        $new = clone $this;
        $new->hazardousWeight = $hazardousWeight;

        return $new;
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
     * @return float
     */
    public function getNetWeight()
    {
        return $this->netWeight;
    }

    /**
     * @param float $netWeight
     * @return Hazardous
     */
    public function withNetWeight($netWeight)
    {
        $new = clone $this;
        $new->netWeight = $netWeight;

        return $new;
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
     * @return int
     */
    public function getFactor()
    {
        return $this->factor;
    }

    /**
     * @param int $factor
     * @return Hazardous
     */
    public function withFactor($factor)
    {
        $new = clone $this;
        $new->factor = $factor;

        return $new;
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
     * @return string
     */
    public function getNotOtherwiseSpecified()
    {
        return $this->notOtherwiseSpecified;
    }

    /**
     * @param string $notOtherwiseSpecified
     * @return Hazardous
     */
    public function withNotOtherwiseSpecified($notOtherwiseSpecified)
    {
        $new = clone $this;
        $new->notOtherwiseSpecified = $notOtherwiseSpecified;

        return $new;
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
}

