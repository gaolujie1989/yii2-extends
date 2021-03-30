<?php

namespace dpd;

use dpd\Type;
use Phpro\SoapClient\Exception\SoapException;
use Phpro\SoapClient\Type\RequestInterface;
use Phpro\SoapClient\Type\ResultInterface;

class ParcelShopFinderServiceClient extends \Phpro\SoapClient\Client
{

    /**
     * @param RequestInterface|Type\FindParcelShopsType $parameters
     * @return ResultInterface|Type\FindParcelShopsResponseType
     * @throws SoapException
     */
    public function findParcelShops(\dpd\Type\FindParcelShopsType $parameters) : \dpd\Type\FindParcelShopsResponseType
    {
        return $this->call('findParcelShops', $parameters);
    }

    /**
     * @param RequestInterface|Type\FindParcelShopsByGeoDataType $parameters
     * @return ResultInterface|Type\FindParcelShopsByGeoDataResponseType
     * @throws SoapException
     */
    public function findParcelShopsByGeoData(\dpd\Type\FindParcelShopsByGeoDataType $parameters) : \dpd\Type\FindParcelShopsByGeoDataResponseType
    {
        return $this->call('findParcelShopsByGeoData', $parameters);
    }

    /**
     * @param RequestInterface|Type\FindCitiesType $parameters
     * @return ResultInterface|Type\FindCitiesResponseType
     * @throws SoapException
     */
    public function findCities(\dpd\Type\FindCitiesType $parameters) : \dpd\Type\FindCitiesResponseType
    {
        return $this->call('findCities', $parameters);
    }

    /**
     * @param RequestInterface|Type\GetAvailableServicesType $parameters
     * @return ResultInterface|Type\GetAvailableServicesResponseType
     * @throws SoapException
     */
    public function getAvailableServices(\dpd\Type\GetAvailableServicesType $parameters) : \dpd\Type\GetAvailableServicesResponseType
    {
        return $this->call('getAvailableServices', $parameters);
    }
}
