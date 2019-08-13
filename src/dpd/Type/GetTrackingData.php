<?php

namespace dpd\Type;

use Phpro\SoapClient\Type\RequestInterface;

class GetTrackingData implements RequestInterface
{

    /**
     * @var string
     */
    private $parcelLabelNumber;

    /**
     * Constructor
     *
     * @var string $parcelLabelNumber
     */
    public function __construct($parcelLabelNumber)
    {
        $this->parcelLabelNumber = $parcelLabelNumber;
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
     * @return GetTrackingData
     */
    public function withParcelLabelNumber($parcelLabelNumber)
    {
        $new = clone $this;
        $new->parcelLabelNumber = $parcelLabelNumber;

        return $new;
    }


}

