<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\amazon\sp;


use DoubleBreak\Spapi\Api\AplusContent;
use DoubleBreak\Spapi\Api\Authorization;
use DoubleBreak\Spapi\Api\CatalogItems;
use DoubleBreak\Spapi\Api\Easy;
use DoubleBreak\Spapi\Api\FbaInboundEligibility;
use DoubleBreak\Spapi\Api\FbaInventory;
use DoubleBreak\Spapi\Api\FbaSmallAndLight;
use DoubleBreak\Spapi\Api\Feeds;
use DoubleBreak\Spapi\Api\Finances;
use DoubleBreak\Spapi\Api\FulfillmentInbound;
use DoubleBreak\Spapi\Api\FulfillmentOutbound;
use DoubleBreak\Spapi\Api\ListingsItems;
use DoubleBreak\Spapi\Api\ListingsRestrictions;
use DoubleBreak\Spapi\Api\MerchantFulfillment;
use DoubleBreak\Spapi\Api\Messaging;
use DoubleBreak\Spapi\Api\Notifications;
use DoubleBreak\Spapi\Api\Orders;
use DoubleBreak\Spapi\Api\ProductFees;
use DoubleBreak\Spapi\Api\ProductPricing;
use DoubleBreak\Spapi\Api\ProductTypeDefinitions;
use DoubleBreak\Spapi\Api\Reports;
use DoubleBreak\Spapi\Api\Sales;
use DoubleBreak\Spapi\Api\Sellers;
use DoubleBreak\Spapi\Api\Services;
use DoubleBreak\Spapi\Api\ShipmentInvoicing;
use DoubleBreak\Spapi\Api\Shipping;
use DoubleBreak\Spapi\Api\Solicitations;
use DoubleBreak\Spapi\Api\Tokens;
use DoubleBreak\Spapi\Api\Uploads;
use DoubleBreak\Spapi\Api\VendorDirectFulfillmentInventory;
use DoubleBreak\Spapi\Api\VendorDirectFulfillmentOrders;
use DoubleBreak\Spapi\Api\VendorDirectFulfillmentPayments;
use DoubleBreak\Spapi\Api\VendorDirectFulfillmentSandboxTestData;
use DoubleBreak\Spapi\Api\VendorDirectFulfillmentShipping;
use DoubleBreak\Spapi\Api\VendorDirectFulfillmentTransactions;
use DoubleBreak\Spapi\Api\VendorInvoices;
use DoubleBreak\Spapi\Api\VendorOrders;
use DoubleBreak\Spapi\Api\VendorShipments;
use DoubleBreak\Spapi\Api\VendorTransactionStatus;
use DoubleBreak\Spapi\Client;
use DoubleBreak\Spapi\Credentials;
use DoubleBreak\Spapi\Signer;
use GuzzleHttp\HandlerStack;
use Iterator;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Yii;
use yii\authclient\CacheStateStorage;
use yii\authclient\StateStorageInterface;
use yii\base\BaseObject;
use yii\base\InvalidArgumentException;
use yii\di\Instance;
use yii\helpers\ArrayHelper;
use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/**
 * Class AmazonSPClient
 *
 * @method AplusContent getAplusContentApi()
 * @method Authorization getAuthorizationApi()
 * @method CatalogItems getCatalogItemsApi()
 * @method Easy getEasyApi()
 * @method FbaInboundEligibility getFbaInboundEligibilityApi()
 * @method FbaInventory getFbaInventoryApi()
 * @method FbaSmallAndLight getFbaSmallAndLightApi()
 * @method Feeds getFeedsApi()
 * @method Finances getFinancesApi()
 * @method FulfillmentInbound getFulfillmentInboundApi()
 * @method FulfillmentOutbound getFulfillmentOutboundApi()
 * @method ListingsItems getListingsItemsApi()
 * @method ListingsRestrictions getListingsRestrictionsApi()
 * @method MerchantFulfillment getMerchantFulfillmentApi()
 * @method Messaging getMessagingApi()
 * @method Notifications getNotificationsApi()
 * @method Orders getOrdersApi()
 * @method ProductFees getProductFeesApi()
 * @method ProductPricing getProductPricingApi()
 * @method ProductTypeDefinitions getProductTypeDefinitionsApi()
 * @method Reports getReportsApi()
 * @method Sales getSalesApi()
 * @method Sellers getSellersApi()
 * @method Services getServicesApi()
 * @method ShipmentInvoicing getShipmentInvoicingApi()
 * @method Shipping getShippingApi()
 * @method Solicitations getSolicitationsApi()
 * @method Tokens getTokensApi()
 * @method Uploads getUploadsApi()
 * @method VendorDirectFulfillmentInventory getVendorDirectFulfillmentInventoryApi()
 * @method VendorDirectFulfillmentOrders getVendorDirectFulfillmentOrdersApi()
 * @method VendorDirectFulfillmentPayments getVendorDirectFulfillmentPaymentsApi()
 * @method VendorDirectFulfillmentSandboxTestData getVendorDirectFulfillmentSandboxTestDataApi()
 * @method VendorDirectFulfillmentShipping getVendorDirectFulfillmentShippingApi()
 * @method VendorDirectFulfillmentTransactions getVendorDirectFulfillmentTransactionsApi()
 * @method VendorInvoices getVendorInvoicesApi()
 * @method VendorOrders getVendorOrdersApi()
 * @method VendorShipments getVendorShipmentsApi()
 * @method VendorTransactionStatus getVendorTransactionStatusApi()
 *
 * @method array getVendorPurchaseOrders
 * @method array getVendorPurchaseOrdersStatus
 *
 * @method array batchVendorPurchaseOrders
 * @method array batchVendorPurchaseOrdersStatus
 *
 * @method array eachVendorPurchaseOrders
 * @method array eachVendorPurchaseOrdersStatus
 *
 * @package lujie\amazon\sp
 * @author Lujie Zhou <gao_lujie@live.cn>
 * @document https://developer-docs.amazon.com/sp-api/docs
 */
class AmazonSPClient extends BaseObject
{
    /**
     * @var array
     */
    public $config = [];

    public $id;
    public $name;

    public $http = [
        'verify' => true,
        'debug' => false
    ];
    public $refreshToken;
    public $clientId;
    public $clientSecret;
    public $accessKey;
    public $accessSecret;
    public $roleARN;
    public $region = AmazonSPConst::REGION_EU_WEST_1;
    public $host = AmazonSPConst::HOST_EU_WEST_1;

    /**
     * @var CacheStateStorage
     */
    public $stateStorage = [
        'class' => CacheStateStorage::class,
    ];

    /**
     * @var StateTokenStorage
     */
    public $tokenStorage;

    /**
     * @var Signer
     */
    public $signer;

    /**
     * @var Credentials
     */
    public $credentials;

    /**
     * @var array
     */
    public $methods = [
        'getVendorPurchaseOrders' => [VendorOrders::class, 'getPurchaseOrders', 'orders'],
        'getVendorPurchaseOrdersStatus' => [VendorOrders::class, 'getPurchaseOrdersStatus', 'orders'],
    ];

    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        if (empty($this->name)) {
            $this->name = Inflector::camel2id(StringHelper::basename(get_class($this)));
        }
        if (empty($this->id)) {
            $this->id = $this->name;
        }
        $this->id .= '_' . $this->clientId;
        $this->initApi();
    }

    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    protected function initApi(): void
    {
//        $this->http['handler'] = $this->getHandler();
        $this->config = [
            //Guzzle configuration
            'http' => $this->http,

            //LWA: Keys needed to obtain access token from Login With Amazon Service
            'refresh_token' => $this->refreshToken,
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,

            //STS: Keys of the IAM role which are needed to generate Secure Session
            // (a.k.a Secure token) for accessing and assuming the IAM role
            'access_key' => $this->accessKey,
            'secret_key' => $this->accessSecret,
            'role_arn' => $this->roleARN,

            //API: Actual configuration related to the SP API :)
            'region' => $this->region,
            'host' => $this->host
        ];
        $this->stateStorage = Instance::ensure($this->stateStorage, StateStorageInterface::class);
        $this->tokenStorage = new StateTokenStorage($this->stateStorage);
        $this->tokenStorage->keyPrefix = $this->id;
        $this->signer = new Signer();
        $this->credentials = new Credentials($this->tokenStorage, $this->signer, $this->config);
    }

    public function getHandler(): HandlerStack
    {
        $handlerStack = HandlerStack::create();
        $handlerStack->push(function (callable $handler) {
            return static function (RequestInterface $request, array $options) use ($handler) {
                Yii::info([$request, $options], __CLASS__);
                return $handler($request, $options)
                    ->then(
                        function (ResponseInterface $response) {
                            return $response;
                        }
                    );
            };
        }, 'yii_log');
        return $handlerStack;
    }

    /**
     * @param string $name
     * @param array $params
     * @return Client|mixed
     * @throws \Exception
     * @inheritdoc
     */
    public function __call($name, $params)
    {
        if (strpos($name, 'get') === 0 && substr($name, -3) === 'Api') {
            $apiClass = 'DoubleBreak\Spapi\Api\\' . substr($name, 3, -3);
            return $this->getApi($apiClass);
        }
        if (strpos($name, 'batch') === 0) {
            $resource = substr($name, 5);
            return $this->batch($resource, $params[0] ?? []);
        }
        if (strpos($name, 'each') === 0) {
            $resource = substr($name, 4);
            return $this->each($resource, $params[0] ?? []);
        }
        return parent::__call($name, $params);
    }

    /**
     * @param string $class
     * @return Client
     * @throws \Exception
     * @inheritdoc
     */
    public function getApi(string $class): Client
    {
        if (!is_subclass_of($class, Client::class)) {
            throw new InvalidArgumentException('Invalid class: ' . $class);
        }
        $credentials = $this->credentials->getCredentials();
        return new $class($credentials, $this->config);
    }

    /**
     * @param string $resource
     * @param array $condition
     * @param int $batchSize
     * @return Iterator
     * @throws \Exception
     * @inheritdoc
     */
    public function batch(string $resource, array $condition = [], int $batchSize = 100): Iterator
    {
        $name = 'get' . ucfirst($resource);
        if (empty($this->methods[$name])) {
            throw new InvalidArgumentException('Unknown resource: ' . $resource);
        }
        [$class, $method, $dataKey] = $this->methods[$name];
        $api = $this->getApi($class);
        if (empty($condition['nextToken'])) {
            $condition['limit'] = $batchSize;
        }
        $response = $api->{$method}($condition);
        $nextToken =  ArrayHelper::getValue($response, 'payload.pagination.nextToken');
        yield from ArrayHelper::getValue($response, 'payload.' . $dataKey);
        if ($nextToken) {
            return $this->batch($resource, ['nextToken' => $nextToken]);
        }
    }

    /**
     * @param string $resource
     * @param array $condition
     * @param int $batchSize
     * @return Iterator
     * @throws \Exception
     * @inheritdoc
     */
    public function each(string $resource, array $condition = [], int $batchSize = 100): Iterator
    {
        $iterator = $this->batch($resource, $condition, $batchSize);
        foreach ($iterator as $items) {
            yield from $items;
        }
    }
}