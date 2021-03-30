<?php

namespace dpd;

use dpd\Type;
use Phpro\SoapClient\Exception\SoapException;
use Phpro\SoapClient\Type\RequestInterface;
use Phpro\SoapClient\Type\ResultInterface;

class ParcelLifeCycleServiceClient extends \Phpro\SoapClient\Client
{

    /**
     * @param RequestInterface|Type\GetTrackingData $parameters
     * @return ResultInterface|Type\GetTrackingDataResponse
     * @throws SoapException
     */
    public function getTrackingData(\dpd\Type\GetTrackingData $parameters) : \dpd\Type\GetTrackingDataResponse
    {
        return $this->call('getTrackingData', $parameters);
    }

    /**
     * @param RequestInterface|Type\GetParcelLabelNumberForWebNumber $parameters
     * @return ResultInterface|Type\GetParcelLabelNumberForWebNumberResponse
     * @throws SoapException
     */
    public function getParcelLabelNumberForWebNumber(\dpd\Type\GetParcelLabelNumberForWebNumber $parameters) : \dpd\Type\GetParcelLabelNumberForWebNumberResponse
    {
        return $this->call('getParcelLabelNumberForWebNumber', $parameters);
    }
}
