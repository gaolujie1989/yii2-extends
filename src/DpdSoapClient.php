<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\dpd;

use lujie\dpd\soap\DpdAuthMiddleware;
use lujie\dpd\soap\LoginServiceClassmap;
use lujie\dpd\soap\LoginServiceClient;
use lujie\dpd\soap\ParcelShopFinderServiceClassmap;
use lujie\dpd\soap\ParcelShopFinderServiceClient;
use lujie\dpd\soap\ShipmentServiceClassmap;
use lujie\dpd\soap\ShipmentServiceClient;
use lujie\dpd\soap\Type\FindCitiesResponseType;
use lujie\dpd\soap\Type\FindCitiesType;
use lujie\dpd\soap\Type\FindParcelShopsByGeoDataResponseType;
use lujie\dpd\soap\Type\FindParcelShopsByGeoDataType;
use lujie\dpd\soap\Type\FindParcelShopsResponseType;
use lujie\dpd\soap\Type\FindParcelShopsType;
use lujie\dpd\soap\Type\GetAuth;
use lujie\dpd\soap\Type\GetAuthResponse;
use lujie\dpd\soap\Type\GetAvailableServicesResponseType;
use lujie\dpd\soap\Type\GetAvailableServicesType;
use lujie\dpd\soap\Type\StoreOrders;
use lujie\dpd\soap\Type\StoreOrdersResponse;
use lujie\extend\caching\CachingTrait;
use Phpro\SoapClient\Client;
use Phpro\SoapClient\Soap\Driver\ExtSoap\ExtSoapEngineFactory;
use Phpro\SoapClient\Soap\Driver\ExtSoap\ExtSoapOptions;
use Phpro\SoapClient\Soap\Handler\HttPlugHandle;
use Symfony\Component\EventDispatcher\EventDispatcher;
use yii\base\Component;

/**
 * Class DpdSoapClient
 *
 * @method GetAuthResponse getAuth(GetAuth $parameters)
 * @method StoreOrdersResponse storeOrders(StoreOrders $parameters)
 * @method FindParcelShopsResponseType findParcelShops(FindParcelShopsType $parameters)
 * @method FindParcelShopsByGeoDataResponseType findParcelShopsByGeoData(FindParcelShopsByGeoDataType $parameters)
 * @method FindCitiesResponseType findCities(FindCitiesType $parameters)
 * @method GetAvailableServicesResponseType getAvailableServices(GetAvailableServicesType $parameters)
 *
 * @package lujie\dpd
 * @author Lujie Zhou <gao_lujie@live.cn>
 * @document https://esolutions.dpd.com/entwickler/dpdwebservices.aspx
 * @document https://esolutions.dpd.com/entwickler/entwicklerdaten/sandbox.aspx
 */
class DpdSoapClient extends Component
{
    use CachingTrait;

    /**
     * @var string
     */
    public $baseUrl = 'https://public-ws-stage.dpd.com/services';

    /**
     * @var string
     */
    public $username = 'sandboxdpd';

    /**
     * @var string
     */
    public $password = 'xMmshh1';

    /**
     * @var string
     */
    public $language = 'en_US';

    /**
     * @var array
     */
    public $clientFactories = [
        'LoginService/V2_0/?wsdl' => [LoginServiceClient::class, LoginServiceClassmap::class],
        'ShipmentService/V4_4/?wsdl' => [ShipmentServiceClient::class, ShipmentServiceClassmap::class],
        'ParcelShopFinderService/V5_0/?wsdl' => [ParcelShopFinderServiceClient::class, ParcelShopFinderServiceClassmap::class],
    ];

    /**
     * @var ShipmentServiceClient[]
     */
    private $clients = [];

    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        $this->initCache();
        foreach ($this->clientFactories as $wsdlPath => [$clientClass, $classMapClass]) {
            $wsdlUrl = trim($this->baseUrl, '/') . '/' . trim($wsdlPath, '/');
            $this->clients[$wsdlPath] = $this->createClient($wsdlUrl, $clientClass, $classMapClass);
        }
    }

    /**
     * @param string $wsdl
     * @param ShipmentServiceClient $clientClass
     * @param ShipmentServiceClassmap $classMapClass
     * @return ShipmentServiceClient
     * @inheritdoc
     */
    protected function createClient(string $wsdl, string $clientClass, string $classMapClass): Client
    {
        $handler = HttPlugHandle::createWithDefaultClient();
        if ($clientClass !== LoginServiceClient::class) {
            $dpdAuthMiddleware = new DpdAuthMiddleware(
                $this->getDpdLogin()->getDelisId(),
                $this->getDpdLogin()->getAuthToken(),
                $this->language
            );
            $handler->addMiddleware($dpdAuthMiddleware);
        }

        $engine = ExtSoapEngineFactory::fromOptionsWithHandler(
            ExtSoapOptions::defaults($wsdl, [])
                ->withClassMap($classMapClass::getCollection()),
            $handler
        );
        $eventDispatcher = new EventDispatcher();

        return new $clientClass($engine, $eventDispatcher);
    }

    /**
     * @return Login
     * @inheritdoc
     */
    public function getDpdLogin(): Login
    {
        $key = $this->baseUrl . $this->username;
        return $this->cache->getOrSet($key, function () {
            $authResponse = $this->getAuth(new GetAuth([
                'delisId' => $this->username,
                'password' => $this->password,
                'messageLanguage' => $this->language,
            ]));
            return $authResponse->getReturn();
        });
    }

    /**
     * @param string $name
     * @param array $params
     * @return mixed
     * @inheritdoc
     */
    public function __call($name, $params)
    {
        foreach ($this->clients as $client) {
            if (method_exists($client, $name)) {
                return call_user_func_array([$client, $name], $params);
            }
        }
        return parent::__call($name, $params);
    }
}
