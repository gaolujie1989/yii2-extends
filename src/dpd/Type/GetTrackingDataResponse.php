<?php

namespace dpd\Type;

use Phpro\SoapClient\Type\ResultInterface;

class GetTrackingDataResponse implements ResultInterface
{

    /**
     * @var \dpd\Type\TrackingResult
     */
    private $trackingresult;

    /**
     * @return \dpd\Type\TrackingResult
     */
    public function getTrackingresult()
    {
        return $this->trackingresult;
    }

    /**
     * @param \dpd\Type\TrackingResult $trackingresult
     * @return GetTrackingDataResponse
     */
    public function withTrackingresult($trackingresult)
    {
        $new = clone $this;
        $new->trackingresult = $trackingresult;

        return $new;
    }


}

