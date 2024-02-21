<?php

namespace lujie\dpd\soap\Type;

use yii\base\BaseObject;

class ParcelInformationType extends BaseObject
{
    /**
     * @var string
     */
    private $parcelLabelNumber;

    /**
     * @var string
     */
    private $dpdReference;

    /**
     * @var \lujie\dpd\soap\Type\OutputType
     */
    private $output;

    /**
     * @return string
     */
    public function getParcelLabelNumber()
    {
        return $this->parcelLabelNumber;
    }

    /**
     * @param string $parcelLabelNumber
     * @return ParcelInformationType
     */
    public function withParcelLabelNumber($parcelLabelNumber)
    {
        $new = clone $this;
        $new->parcelLabelNumber = $parcelLabelNumber;

        return $new;
    }

    /**
     * @param string $parcelLabelNumber
     * @return $this
     */
    public function setParcelLabelNumber(string $parcelLabelNumber) : \lujie\dpd\soap\Type\ParcelInformationType
    {
        $this->parcelLabelNumber = $parcelLabelNumber;
        return $this;
    }

    /**
     * @return string
     */
    public function getDpdReference()
    {
        return $this->dpdReference;
    }

    /**
     * @param string $dpdReference
     * @return ParcelInformationType
     */
    public function withDpdReference($dpdReference)
    {
        $new = clone $this;
        $new->dpdReference = $dpdReference;

        return $new;
    }

    /**
     * @param string $dpdReference
     * @return $this
     */
    public function setDpdReference(string $dpdReference) : \lujie\dpd\soap\Type\ParcelInformationType
    {
        $this->dpdReference = $dpdReference;
        return $this;
    }

    /**
     * @return \lujie\dpd\soap\Type\OutputType
     */
    public function getOutput()
    {
        return $this->output;
    }

    /**
     * @param \lujie\dpd\soap\Type\OutputType $output
     * @return ParcelInformationType
     */
    public function withOutput($output)
    {
        $new = clone $this;
        $new->output = $output;

        return $new;
    }

    /**
     * @param \lujie\dpd\soap\Type\OutputType $output
     * @return $this
     */
    public function setOutput($output) : \lujie\dpd\soap\Type\ParcelInformationType
    {
        $this->output = $output;
        return $this;
    }
}

