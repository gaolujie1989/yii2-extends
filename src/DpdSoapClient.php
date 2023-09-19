<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\dpd;

use Http\Client\Common\PluginClient;
use lujie\dpd\soap\LoginServiceClassmap;
use lujie\dpd\soap\LoginServiceClient;
use lujie\dpd\soap\ParcelShopFinderServiceClassmap;
use lujie\dpd\soap\ParcelShopFinderServiceClient;
use lujie\dpd\soap\ShipmentServiceClassmap;
use lujie\dpd\soap\ShipmentServiceClient;
use lujie\dpd\soap\Type\GetAuth;
use lujie\dpd\soap\Type\Login;
use lujie\extend\caching\CachingTrait;
use lujie\extend\psr\http\Yii2HttpHandler;
use Phpro\SoapClient\Caller\EngineCaller;
use Phpro\SoapClient\Caller\EventDispatchingCaller;
use Phpro\SoapClient\Soap\DefaultEngineFactory;
use Soap\ExtSoapEngine\ExtSoapOptions;
use Soap\ExtSoapEngine\Wsdl\Naming\Md5Strategy;
use Soap\ExtSoapEngine\Wsdl\PermanentWsdlLoaderProvider;
use Soap\Psr18Transport\Psr18Transport;
use Soap\Wsdl\Loader\FlatteningLoader;
use Soap\Wsdl\Loader\StreamWrapperLoader;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Yii;
use yii\base\Component;
use yii\di\Instance;
use yii\helpers\FileHelper;

/**
 * Class DpdSoapClient
 *
 * @method \lujie\dpd\soap\Type\GetAuthResponse getAuth(\lujie\dpd\soap\Type\GetAuth $parameters)
 * @method \lujie\dpd\soap\Type\StoreOrdersResponse storeOrders(\lujie\dpd\soap\Type\StoreOrders $parameters)
 * @method \lujie\dpd\soap\Type\FindParcelShopsResponseType findParcelShops(\lujie\dpd\soap\Type\FindParcelShopsType $parameters)
 * @method \lujie\dpd\soap\Type\FindParcelShopsByGeoDataResponseType findParcelShopsByGeoData(\lujie\dpd\soap\Type\FindParcelShopsByGeoDataType $parameters)
 * @method \lujie\dpd\soap\Type\FindCitiesResponseType findCities(\lujie\dpd\soap\Type\FindCitiesType $parameters)
 * @method \lujie\dpd\soap\Type\GetAvailableServicesResponseType getAvailableServices(\lujie\dpd\soap\Type\GetAvailableServicesType $parameters)
 *
 * @package lujie\dpd
 * @author Lujie Zhou <gao_lujie@live.cn>
 * @document https://esolutions.dpd.com/entwickler/dpdwebservices.aspx
 * @document https://esolutions.dpd.com/entwickler/entwicklerdaten/sandbox.aspx
 */
class DpdSoapClient extends Component
{
    use CachingTrait;
    use DpdSoapClientExtendTrait;

    /**
     * @var string
     */
    public $baseUrl = 'https://public-ws.dpd.com/services';

    /**
     * @var string
     */
    public $sandboxUrl = 'https://public-ws-stage.dpd.com/services';

    /**
     * @var string
     */
    public $productionUrl = 'https://public-ws.dpd.com/services';

    /**
     * @var bool
     */
    protected $sandbox = false;

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
     * @var Yii2HttpHandler
     */
    public $httpHandler = [];

    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        $this->httpHandler = Instance::ensure($this->httpHandler, Yii2HttpHandler::class);
    }

    /**
     * @param bool $sandbox
     * @inheritdoc
     */
    public function setSandbox(bool $sandbox = true): void
    {
        $this->sandbox = $sandbox;
        $this->baseUrl = $this->sandbox ? $this->sandboxUrl : $this->productionUrl;
    }

    /**
     * @return Login
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function getDpdLogin(): Login
    {
        $key = $this->baseUrl . $this->username;
        return $this->getOrSetCacheValue($key, function () {
            $authResponse = $this->getAuth(new GetAuth([
                'delisId' => $this->username,
                'password' => $this->password,
                'messageLanguage' => $this->language,
            ]));
            return $authResponse->getReturn();
        });
    }

    /**
     * @return string
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function getSendingDepot(): string
    {
        return $this->getDpdLogin()->getDepot();
    }

    /**
     * @param string $wsdl
     * @param LoginServiceClient|ShipmentServiceClient|ParcelShopFinderServiceClient $clientClass
     * @param LoginServiceClassmap|ShipmentServiceClassmap|ParcelShopFinderServiceClassmap $classMapClass
     * @return LoginServiceClient|ShipmentServiceClient|ParcelShopFinderServiceClient
     * @throws \yii\base\Exception
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    protected function createClient(string $wsdl, string $clientClass, string $classMapClass): mixed
    {
        $plugins = [];
        if ($clientClass !== LoginServiceClient::class) {
            $dpdLogin = $this->getDpdLogin();
            $plugins = [
                new DpdSoapAuthPlugin($dpdLogin->getDelisId(), $dpdLogin->getAuthToken(), $this->language),
            ];
        }

        $cacheDir = Yii::getAlias('@runtime/wsdl');
        FileHelper::createDirectory($cacheDir);
        $engine = DefaultEngineFactory::create(
            ExtSoapOptions::defaults($wsdl, [])
                ->withWsdlProvider(new PermanentWsdlLoaderProvider(
                    new FlatteningLoader(new StreamWrapperLoader()),
                    new Md5Strategy(),
                    $cacheDir
                ))
                ->withClassMap($classMapClass::getCollection()),
            Psr18Transport::createForClient(
                new PluginClient(
                    $this->httpHandler,
                    $plugins
                )
            ),
        );

        $eventDispatcher = new EventDispatcher();
        $caller = new EventDispatchingCaller(new EngineCaller($engine), $eventDispatcher);

        return new $clientClass($caller);
    }

    /**
     * @param $name
     * @param $params
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function __call($name, $params)
    {
        foreach ($this->clientFactories as $wsdlPath => [$clientClass, $classMapClass]) {
            if (empty($this->clients[$wsdlPath])) {
                $wsdlUrl = trim($this->baseUrl, '/') . '/' . trim($wsdlPath, '/');
                $this->clients[$wsdlPath] = $this->createClient($wsdlUrl, $clientClass, $classMapClass);
            }
            $client = $this->clients[$wsdlPath];
            if (method_exists($client, $name)) {
                return call_user_func_array([$client, $name], $params);
            }
        }
        return parent::__call($name, $params);
    }
}
