<?php

namespace dpd\Type;

use Phpro\SoapClient\Type\ResultInterface;

class GetParcelLabelNumberForWebNumberResponse implements ResultInterface
{

    /**
     * @var string
     */
    private $parcelLabelNumber;

    /**
     * @return string
     */
    public function getParcelLabelNumber()
    {
        return $this->parcelLabelNumber;
    }

    /**
     * @param string $parcelLabelNumber
     * @return GetParcelLabelNumberForWebNumberResponse
     */
    public function withParcelLabelNumber($parcelLabelNumber)
    {
        $new = clone $this;
        $new->parcelLabelNumber = $parcelLabelNumber;

        return $new;
    }


}

