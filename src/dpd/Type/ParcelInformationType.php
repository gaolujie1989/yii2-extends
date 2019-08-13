<?php

namespace dpd\Type;

use Phpro\SoapClient\Type\RequestInterface;

class ParcelInformationType implements RequestInterface
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
     * Constructor
     *
     * @var string $parcelLabelNumber
     * @var string $dpdReference
     */
    public function __construct($parcelLabelNumber, $dpdReference)
    {
        $this->parcelLabelNumber = $parcelLabelNumber;
        $this->dpdReference = $dpdReference;
    }

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


}

