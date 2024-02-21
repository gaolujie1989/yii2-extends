<?php

namespace lujie\dpd\soap;

use Phpro\SoapClient\Caller\Caller;
use lujie\dpd\soap\Type;
use Phpro\SoapClient\Type\ResultInterface;
use Phpro\SoapClient\Exception\SoapException;
use Phpro\SoapClient\Type\RequestInterface;

class ParcelShopFinderServiceClient
{
    /**
     * @var Caller
     */
    private $caller;

    public function __construct(\Phpro\SoapClient\Caller\Caller $caller)
    {
        $this->caller = $caller;
    }

    /**
     * @param RequestInterface|Type\FindParcelShopsType $parameters
     * @return ResultInterface|Type\FindParcelShopsResponseType
     * @throws SoapException
     */
    public function findParcelShops(\lujie\dpd\soap\Type\FindParcelShopsType $parameters) : \lujie\dpd\soap\Type\FindParcelShopsResponseType
    {
        return ($this->caller)('findParcelShops', $parameters);
    }

    /**
     * @param RequestInterface|Type\FindParcelShopsByGeoDataType $parameters
     * @return ResultInterface|Type\FindParcelShopsByGeoDataResponseType
     * @throws SoapException
     */
    public function findParcelShopsByGeoData(\lujie\dpd\soap\Type\FindParcelShopsByGeoDataType $parameters) : \lujie\dpd\soap\Type\FindParcelShopsByGeoDataResponseType
    {
        return ($this->caller)('findParcelShopsByGeoData', $parameters);
    }

    /**
     * @param RequestInterface|Type\FindCitiesType $parameters
     * @return ResultInterface|Type\FindCitiesResponseType
     * @throws SoapException
     */
    public function findCities(\lujie\dpd\soap\Type\FindCitiesType $parameters) : \lujie\dpd\soap\Type\FindCitiesResponseType
    {
        return ($this->caller)('findCities', $parameters);
    }

    /**
     * @param RequestInterface|Type\GetAvailableServicesType $parameters
     * @return ResultInterface|Type\GetAvailableServicesResponseType
     * @throws SoapException
     */
    public function getAvailableServices(\lujie\dpd\soap\Type\GetAvailableServicesType $parameters) : \lujie\dpd\soap\Type\GetAvailableServicesResponseType
    {
        return ($this->caller)('getAvailableServices', $parameters);
    }
}

