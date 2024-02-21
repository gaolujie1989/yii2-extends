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
use lujie\extend\authclient\BaseSoapClient;
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
class DpdSoapClient extends BaseSoapClient
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
     * @param string $clientClass
     * @return array|DpdSoapAuthPlugin[]
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function getHttpPlugins(string $clientClass): array
    {
        if ($clientClass !== LoginServiceClient::class) {
            $dpdLogin = $this->getDpdLogin();
            return [
                new DpdSoapAuthPlugin($dpdLogin->getDelisId(), $dpdLogin->getAuthToken(), $this->language),
            ];
        }
        return [];
    }
}
