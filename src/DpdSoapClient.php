<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\dpd;

use dpd\LoginServiceClassmap;
use dpd\LoginServiceClient;
use dpd\ParcelLifeCycleServiceClassmap;
use dpd\ParcelLifeCycleServiceClient;
use dpd\ParcelShopFinderServiceClassmap;
use dpd\ParcelShopFinderServiceClient;
use dpd\ShipmentServiceClassmap;
use dpd\ShipmentServiceClient;
use dpd\Type\GetAuth;
use dpd\Type\Login;
use lujie\dpd\soap\DpdAuthMiddleware;
use lujie\extend\caching\CachingTrait;
use Phpro\SoapClient\Client;
use Phpro\SoapClient\Soap\Driver\ExtSoap\ExtSoapEngineFactory;
use Phpro\SoapClient\Soap\Driver\ExtSoap\ExtSoapOptions;
use Phpro\SoapClient\Soap\Handler\HttPlugHandle;
use Symfony\Component\EventDispatcher\EventDispatcher;
use yii\base\Component;

/**
 * Class GlsSoapClient
 *
 * @method \dpd\Type\GetAuthResponse getAuth(\dpd\Type\GetAuth $parameters)
 * @method \dpd\Type\StoreOrdersResponse storeOrders(\dpd\Type\StoreOrders $parameters)
 * @method \dpd\Type\GetTrackingDataResponse getTrackingData(\dpd\Type\GetTrackingData $parameters) :
 * @method \dpd\Type\GetParcelLabelNumberForWebNumberResponse getParcelLabelNumberForWebNumber(\dpd\Type\GetParcelLabelNumberForWebNumber $parameters)
 * @method \dpd\Type\FindParcelShopsResponseType findParcelShops(\dpd\Type\FindParcelShopsType $parameters)
 * @method \dpd\Type\FindParcelShopsByGeoDataResponseType findParcelShopsByGeoData(\dpd\Type\FindParcelShopsByGeoDataType $parameters)
 * @method \dpd\Type\FindCitiesResponseType findCities(\dpd\Type\FindCitiesType $parameters)
 * @method \dpd\Type\GetAvailableServicesResponseType getAvailableServices(\dpd\Type\GetAvailableServicesType $parameters)
 *
 * https://esolutions.dpd.com/entwickler/entwicklerdaten/sandbox.aspx
 * @package lujie\gls
 * @author Lujie Zhou <gao_lujie@live.cn>
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
        'ShipmentService/V3_2/?wsdl' => [ShipmentServiceClient::class, ShipmentServiceClassmap::class],
        'ParcelLifeCycleService/V2_0/?wsdl' => [ParcelLifeCycleServiceClient::class, ParcelLifeCycleServiceClassmap::class],
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
    protected function createClient($wsdl, $clientClass, $classMapClass): Client
    {
        $handler = HttPlugHandle::createWithDefaultClient();
        if ($clientClass !== LoginServiceClient::class) {
            $dpdAuthMiddleware = new DpdAuthMiddleware(
                $this->getDpdLogin()->getDelisId(),
                $this->getDpdLogin()->getAuthToken(),
                $this->language);
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
        return $this->cache->getOrSet($key, function() {
            $authResponse = $this->getAuth(new GetAuth($this->username, $this->password, $this->language));
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
