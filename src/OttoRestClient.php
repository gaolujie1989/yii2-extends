<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\otto;

use lujie\extend\authclient\RestOAuth2;
use yii\authclient\OAuthToken;
use yii\helpers\ArrayHelper;
use yii\httpclient\Client;

/**
 * Class OttoRestClient
 *
 * @method array listV3Products($data = [])
 * @method \Generator eachV3Products($condition = [], $batchSize = 100)
 * @method \Generator batchV3Products($condition = [], $batchSize = 100)
 * @method array getV3Product($data)
 * @method array saveV3Product($data)
 *
 * @method array listV3ProductActiveStatuses($data = [])
 * @method \Generator eachV3ProductActiveStatuses($condition = [], $batchSize = 100)
 * @method \Generator batchV3ProductActiveStatuses($condition = [], $batchSize = 100)
 * @method array updateV3ProductActiveStatus($data)
 *
 * @method array listV3ProductBrands($data = [])
 * @method \Generator eachV3ProductBrands($condition = [], $batchSize = 100)
 * @method \Generator batchV3ProductBrands($condition = [], $batchSize = 100)
 *
 * @method array listV3ProductCategories($data = [])
 * @method \Generator eachV3ProductCategories($condition = [], $batchSize = 100)
 * @method \Generator batchV3ProductCategories($condition = [], $batchSize = 100)
 *
 * @method array listV3ProductContentChanges($data = [])
 * @method \Generator eachV3ProductContentChanges($condition = [], $batchSize = 100)
 * @method \Generator batchV3ProductContentChanges($condition = [], $batchSize = 100)
 *
 * @method array listV3ProductMarketplaceStatuses($data = [])
 * @method \Generator eachV3ProductMarketplaceStatuses($condition = [], $batchSize = 100)
 * @method \Generator batchV3ProductMarketplaceStatuses($condition = [], $batchSize = 100)
 *
 * @method array listV3ProductPrices($data = [])
 * @method \Generator eachV3ProductPrices($condition = [], $batchSize = 100)
 * @method \Generator batchV3ProductPrices($condition = [], $batchSize = 100)
 * @method array updateV3ProductPrice($data)
 *
 * @method array listV2Quantities($data = [])
 * @method \Generator eachV2Quantities($condition = [], $batchSize = 100)
 * @method \Generator batchV2Quantities($condition = [], $batchSize = 100)
 * @method array getV2Quantity($data)
 * @method array saveV2Quantity($data)
 *
 * @method array listV4Orders($data = [])
 * @method \Generator eachV4Orders($condition = [], $batchSize = 100)
 * @method \Generator batchV4Orders($condition = [], $batchSize = 100)
 * @method array getV4Order($data)
 * @method array cancelV4Order($data)
 *
 * @method array listV1Shipments($data = [])
 * @method \Generator eachV1Shipments($condition = [], $batchSize = 100)
 * @method \Generator batchV1Shipments($condition = [], $batchSize = 100)
 * @method array getV1Shipment($data)
 * @method array createV1Shipment($data)
 * @method array correctV1Shipment($data)
 *
 * @method array listV2Returns($data = [])
 * @method \Generator eachV2Returns($condition = [], $batchSize = 100)
 * @method \Generator batchV2Returns($condition = [], $batchSize = 100)
 * @method array acceptV2Return($data)
 * @method array rejectV2Return($data)
 *
 * @method array listV3Receipts($data = [])
 * @method \Generator eachV3Receipts($condition = [], $batchSize = 100)
 * @method \Generator batchV3Receipts($condition = [], $batchSize = 100)
 * @method array getV3Receipt($data)
 * @method array downloadV3Receipt($data)
 *
 * @method array getV3SingleProductActiveStatus($data)
 * @method array getV3SingleProductContentChange($data)
 * @method array getV3SingleProductMarketplaceStatus($data)
 * @method array getV3ProductUpdateTask($data)
 * @method array getV3ProductUpdateTaskStatus($data)
 * @method array getV3ProductUpdateTaskFailed($data)
 * @method array getV3ProductUpdateTaskSucceeded($data)
 * @method array getV3ProductUpdateTaskUnchanged($data)
 * @method array getV4OrderByOrderNumber($data)
 * @method array cancelV4OrderItems($data)
 * @method array getV1ShipmentsByCarrierAndTrackingNumber($data)
 * @method array correctV1ShipmentsByCarrierAndTrackingNumber($data)
 *
 * @package lujie\otto
 * @author Lujie Zhou <gao_lujie@live.cn>
 * @document https://api.otto.market/docs
 */
class OttoRestClient extends RestOAuth2
{
    public $sandboxUrlMap = ['api.otto.market' => 'sandbox.api.otto.market'];

    public $apiBaseUrl = 'https://api.otto.market/';

    public $tokenUrl = 'v1/token';

    public $clientId = 'token-otto-api';

    public $username;

    public $password;

    /**
     * @var array
     */
    public $resources = [
        'V3Product' => 'v3/products',
        'V3ProductActiveStatus' => 'v3/products/active-status',
        'V3ProductBrand' => 'v3/products/brands',
        'V3ProductCategory' => 'v3/products/categories',
        'V3ProductContentChange' => 'v3/products/content-changes',
        'V3ProductMarketplaceStatus' => 'v3/products/marketplace-status',
        'V3ProductPrice' => 'v3/products/prices',
        'V2Quantity' => 'v2/quantities',
        'V4Order' => 'v4/orders',
        'V1Shipment' => 'v1/shipments',
        'V2Return' => 'v2/returns',
        'V3Receipt' => 'v3/receipt',
    ];

    public $extraActions = [
        'V3Product' => [
            'get' => ['GET', '{sku}'],
            'save' => ['POST', ''],
        ],
        'V3ProductActiveStatus' => [
            'update' => ['POST', ''],
        ],
        'V3ProductPrice' => [
            'update' => ['POST', ''],
        ],
        'V2Quantity' => [
            'get' => ['GET', '{sku}'],
            'save' => ['POST', ''],
        ],
        'V4Order' => [
            'get' => ['GET', '{salesOrderId}'],
            'cancel' => ['POST', '{salesOrderId}/cancellation'],
        ],
        'V1Shipment' => [
            'get' => ['GET', '{shipmentId}'],
            'create' => ['POST', ''],
            'correct' => ['POST', '{shipmentId}/positionItems'],
        ],
        'V2Return' => [
            'accept' => ['POST', 'acceptance'],
            'reject' => ['POST', 'rejection'],
        ],
        'V3Receipt' => [
            'get' => ['GET', '{receiptNumber}'],
            'download' => ['GET', '{receiptNumber}.pdf'],
        ]
    ];

    public $extraMethods = [
        'getV3SingleProductActiveStatus' => ['GET', 'v3/products/{sku}/active-status'],
        'getV3SingleProductContentChange' => ['GET', 'v3/products/{sku}/content-changes'],
        'getV3SingleProductMarketplaceStatus' => ['GET', 'v3/products/{sku}/marketplace-status'],
        'getV3ProductUpdateTask' => ['GET', 'v3/products/update-tasks/{processUuid}'],
        'getV3ProductUpdateTaskStatus' => ['GET', 'v3/products/update-tasks/{processUuid}/{status}'],
        'getV3ProductUpdateTaskFailed' => ['GET', 'v3/products/update-tasks/{processUuid}/failed'],
        'getV3ProductUpdateTaskSucceeded' => ['GET', 'v3/products/update-tasks/{processUuid}/succeeded'],
        'getV3ProductUpdateTaskUnchanged' => ['GET', 'v3/products/update-tasks/{processUuid}/unchanged'],
        'getV4OrderByOrderNumber' => ['GET', 'v4/orders/{orderNumber}'],
        'cancelV4OrderItems' => ['GET', 'v4/orders/{salesOrderId}/positionItems/{positionItemIds}/cancellation'],
        'getV1ShipmentsByCarrierAndTrackingNumber' => ['GET', 'v1/shipments/carriers/{carrier}/trackingnumbers/{trackingNumbers}'],
        'correctV1ShipmentsByCarrierAndTrackingNumber' => ['GET', 'v1/shipments/carriers/{carrier}/trackingnumbers/{trackingNumbers}/positionItems'],
    ];

    public $requestDataKeys = [
        'updateV3ProductActiveStatus' => 'status',
    ];

    public $responseDataKeys = [
        'listV3Products' => 'productVariations',
        'listV3ProductActiveStatuses' => 'status',
        'listV3ProductBrands' => 'brands',
        'listV3ProductCategories' => 'categoryGroups',
        'listV3ProductContentChanges' => 'contentChanges',
        'listV3ProductMarketplaceStatuses' => 'marketPlaceStatus',
        'listV3ProductPrices' => 'variationPrices',
        'listV2Quantities' => 'resources.variations',
        'listV4Orders' => 'resources',
        'listV1Shipments' => 'resources',
        'listV2Returns' => 'positionItems',
        'listV3Receipts' => 'resources',
        'getV3SingleProductContentChange' => 'contentChanges',
        'getV3ProductUpdateTask' => '',
        'getV3ProductUpdateTaskFailed' => 'results',
        'getV3ProductUpdateTaskSucceeded' => 'results',
        'getV3ProductUpdateTaskUnchanged' => 'results',
    ];

    public function init(): void
    {
        $this->actions = [
            'list' => ['GET', ''],
        ];
        parent::init();
        $this->initRest();
    }

    #region Auth token

    /**
     * @return OAuthToken
     * @inheritdoc
     */
    public function getAccessToken(): OAuthToken
    {
        $authToken = parent::getAccessToken();
        if (!is_object($authToken)) {
            $authToken = $this->authenticateUser($this->username, $this->password);
        }
        return $authToken;
    }

    /**
     * @param OAuthToken $token
     * @return OAuthToken
     * @inheritdoc
     */
    public function refreshAccessToken(OAuthToken $token): OAuthToken
    {
        $refreshExpiresAt = ($token->getParam('refresh_expires_in') ?: 0) + $token->createTimestamp - 5;
        if ($refreshExpiresAt > time()) {
            return parent::refreshAccessToken($token);
        }
        return $this->authenticateUser($this->username, $this->password);
    }

    /**
     * @param \yii\httpclient\Request $request
     * @inheritdoc
     */
    protected function applyClientCredentialsToRequest($request): void
    {
        if ($request->getUrl() === $this->tokenUrl) {
            $request->addData(['client_id' => $this->clientId]);
            $request->format = Client::FORMAT_URLENCODED;
        }
    }

    #endregion

    /**
     * @param string $name
     * @param array $data
     * @return array|null
     * @throws \Exception
     * @inheritdoc
     */
    public function restApi(string $name, array $data): ?array
    {
        $requestDataKey = $this->requestDataKeys[$name] ?? null;
        if ($requestDataKey) {
            $data = [$requestDataKey => $data];
        }
        return parent::restApi($name, $data);
    }

    #region Batch

    /**
     * @param array $responseData
     * @param string $method
     * @return array
     * @throws \Exception
     * @inheritdoc
     */
    protected function getPageData(array $responseData, string $method): array
    {
        $responseDataKey = $this->responseDataKeys[$method] ?? null;
        if ($responseDataKey) {
            return ArrayHelper::getValue($responseData, $responseDataKey);
        }
        return $responseData;
    }

    /**
     * @param array $responseData
     * @param array $condition
     * @return array|null
     * @inheritdoc
     */
    protected function getNextPageCondition(array $responseData, array $condition): ?array
    {
        $links = ArrayHelper::map($responseData['links'], 'rel', 'href');
        if (empty($links['next'])) {
            return null;
        }
        return $this->getNextByLink($links['next']);
    }

    #endregion
}