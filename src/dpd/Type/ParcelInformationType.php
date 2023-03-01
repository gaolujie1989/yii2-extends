<?php

namespace dpd\Type;

class ParcelInformationType
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
     * @var \dpd\Type\OutputType
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
     * @return \dpd\Type\OutputType
     */
    public function getOutput()
    {
        return $this->output;
    }

    /**
     * @param \dpd\Type\OutputType $output
     * @return ParcelInformationType
     */
    public function withOutput($output)
    {
        $new = clone $this;
        $new->output = $output;

        return $new;
    }


}

