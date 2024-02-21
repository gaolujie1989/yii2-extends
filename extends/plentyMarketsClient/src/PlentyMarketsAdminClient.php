<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\plentyMarkets;

use lujie\extend\authclient\BaseCookieClient;
use yii\base\InvalidConfigException;
use yii\helpers\Json;
use yii\httpclient\Response;
use yii\web\CookieCollection;

/**
 * Class PlentyMarketsDynamicExport
 * @package lujie\plentyMarkets
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class PlentyMarketsAdminClient extends BaseCookieClient
{
    /**
     * @var int
     */
    public $plentyId;

    /**
     * @var string
     */
    public $loginUrl = 'https://plentymarkets-cloud-de.com/';

    /**
     * @var string
     */
    public $guiCallUrl = 'https://{domainHash}.plentymarkets-cloud-de.com/plenty/admin/gui_call.php';

    /**
     * @var string
     */
    public $apiUiUrl = 'https://{domainHash}.plentymarkets-cloud-de.com/plenty/api/ui.php';

    /**
     * @var array
     */
    private $sessionData = [];

    #region

    /**
     * @return array
     * @inheritdoc
     */
    protected function initUserAttributes(): array
    {
        return [];
    }

    /**
     * @param array $params
     * @return CookieCollection
     * @throws InvalidConfigException
     * @throws \yii\httpclient\Exception
     * @inheritdoc
     */
    public function login(array $params = []): CookieCollection
    {
        if (empty($params)) {
            $params = [
                'safemode' => 0,
                'terraRoute' => '/' . $this->plentyId,
                'queryParams' => '',
                'language' => '',
                'pid' => $this->plentyId,
                'username' => $this->username,
                'password' => $this->password,
            ];
        }
        parent::login($params);
        $this->setSessionID();
        return $this->getCookies();
    }

    /**
     * @return string|null
     * @throws InvalidConfigException
     * @throws \yii\httpclient\Exception
     * @inheritdoc
     */
    protected function getDomainHash(): ?string
    {
        $cookies = $this->getCookies();
        $value = $cookies->getValue('domainHash');
        if (empty($value)) {
            $cookies = $this->login();
            $value = $cookies->getValue('domainHash');
        }
        return $value;
    }

    /**
     * @param CookieCollection $cookies
     * @inheritdoc
     */
    public function setCookies(CookieCollection $cookies): void
    {
        $defaultExpireAt = time() + $this->expireDuration;
        foreach ($cookies as $cookie) {
            $cookie->expire = $defaultExpireAt;
        }
        parent::setCookies($cookies);
    }

    /**
     * @return string
     * @throws InvalidConfigException
     * @throws \yii\httpclient\Exception
     * @inheritdoc
     */
    protected function getAuthorization(): string
    {
        $cookies = $this->getCookies();
        foreach ($cookies as $cookie) {
            if (str_starts_with($cookie->name, 'at') && strlen($cookie->name) === 7) {
                return $cookie->value;
            }
        }
        throw new InvalidConfigException('Invalid Cookies');
    }

    /**
     * @throws InvalidConfigException
     * @throws \yii\httpclient\Exception
     * @inheritdoc
     */
    protected function setSessionID(): void
    {
        $sessionRequestData = [
            'requests' => [
                [
                    '_dataName' => 'PlentyMarketsLogin',
                    '_moduleName' => 'user/login',
                    '_searchParams' => [],
                    '_writeParams' => [],
                    '_validateParams' => [],
                    '_commandStack' => [
                        [
                            'type' => 'read',
                            'command' => 'read',
                        ],
                    ],
                    '_dataArray' => [],
                    '_dataList' => [],
                ],
            ],
            'meta' => [
                'token' => '',
            ],
        ];
        $sessionRequestData = ['request' => Json::encode($sessionRequestData)];
        $header = [
            'Authorization' => 'Bearer ' . $this->getAuthorization(),
            'Referer' => strtr(
                'https://{domainHash}.plentymarkets-cloud-de.com/plenty/gwt/productive/2318cd3d/admin.html',
                ['{domainHash}' => $this->getDomainHash()]
            ),
        ];
        $sessionUrl = strtr($this->apiUiUrl, ['{domainHash}' => $this->getDomainHash()]);
        $response = $this->createRequest()
            ->setMethod('POST')
            ->setUrl($sessionUrl)
            ->setData($sessionRequestData)
            ->addHeaders($header)
            ->send();
        $sessionIDCookie = $response->getCookies()->get('SID_PLENTY_ADMIN_' . $this->plentyId);
        $cookies = $this->getCookies();
        $cookies->add($sessionIDCookie);
        $this->setCookies($cookies);

        $responseData = $response->getData();
        $this->setSessionData($responseData['resultObjects'][0]['_data'][0]['_dataArray'] ?? []);
    }

    /**
     * @param array $sessionData
     */
    public function setSessionData(array $sessionData): void
    {
        $this->sessionData = $sessionData;
        $this->setState('session', $sessionData);
    }

    /**
     * @return array
     * @throws InvalidConfigException
     * @throws \yii\httpclient\Exception
     * @inheritdoc
     */
    public function getSessionData(): array
    {
        if (empty($this->sessionData)) {
            $this->getCookies(); //for expire login
            $this->sessionData = $this->getState('session');
        }
        return $this->sessionData;
    }

    #endregion

    #region admin method

    /**
     * @param string $name
     * @param int $offset
     * @param int $rowCount
     * @return string
     * @throws \yii\authclient\InvalidResponseException
     * @throws \yii\httpclient\Exception
     * @inheritdoc
     */
    public function dynamicExport(string $name = 'OrderCompleteAllField', $offset = 0, $rowCount = 6000): string
    {
        $query = [
            'Object' => 'mod_export@GuiDynamicFieldExportView2',
            'Params' => [
                'gui' => 'AjaxExportData',
            ],
            'gwt_tab_id' => '',
            'presenter_id' => '',
            'action' => 'ExportDataFormat',
            'formatDynamicUserName' => $name,
            'offset' => $offset,
            'rowCount' => $rowCount,
            'deletedOrderOption' => '0',
            'stockBarcodeOption' => '1',
        ];
        $requestUrl = strtr($this->guiCallUrl, ['{domainHash}' => $this->getDomainHash()]) . '?' . http_build_query($query);
        return $this->request($requestUrl)->content;
    }

    /**
     * @param int $orderId
     * @return Response
     * @throws \yii\authclient\InvalidResponseException
     * @throws \yii\httpclient\Exception
     * @inheritdoc
     */
    public function deleteOrderStorageLocation(int $orderId): Response
    {
        $data = [
            'Object' => 'mod_order@GuiOrderDetails',
            'Params' => [
                'gui' => 'AjaxOverviewTabContent',
                'result_id' => 'ArticlePosTabContent_' . $orderId,
            ],
            'gwt_tab_id' => '',
            'presenter_id' => '1',
            'action' => 'unbindPositions',
            'o_id' => $orderId,
            'additional_id' => '',
        ];
        $requestUrl = strtr($this->guiCallUrl, ['{domainHash}' => $this->getDomainHash()]);
        return $this->request($requestUrl, 'POST', $data);
    }

    /**
     * @param int $orderId
     * @return Response
     * @throws \yii\authclient\InvalidResponseException
     * @throws \yii\httpclient\Exception
     * @inheritdoc
     */
    public function assignOrderStorageLocation(int $orderId): Response
    {
        $data = [
            'Object' => 'mod_order@GuiOrderDetails',
            'Params' => [
                'gui' => 'AjaxOverviewTabContent',
                'result_id' => 'ArticlePosTabContent_' . $orderId,
            ],
            'gwt_tab_id' => '',
            'presenter_id' => '1',
            'action' => 'bindPositions',
            'o_id' => $orderId,
            'additional_id' => '',
        ];
        $requestUrl = strtr($this->guiCallUrl, ['{domainHash}' => $this->getDomainHash()]);
        return $this->request($requestUrl, 'POST', $data);
    }

    /**
     * @param int $orderId
     * @param int $orderItemId
     * @return Response
     * @throws \yii\authclient\InvalidResponseException
     * @throws \yii\httpclient\Exception
     * @inheritdoc
     */
    public function deleteOrderItem(int $orderId, int $orderItemId): Response
    {
        $data = [
            'Object' => 'mod_order@GuiOrderDetails',
            'Params' => [
                'gui' => 'AjaxRowEditTableRow',
                'result_id' => 'OrderEditPositionsPane_' . $orderItemId,
            ],
            'gwt_tab_id' => '',
            'presenter_id' => '1',
            'action' => 'deleteOrderRow',
            'o_id' => $orderId,
            'order_row_id' => $orderItemId,
            'additional_id' => '',
        ];
        $requestUrl = strtr($this->guiCallUrl, ['{domainHash}' => $this->getDomainHash()]);
        return $this->request($requestUrl, 'POST', $data);
    }

    /**
     * @param int $orderId
     * @param int $orderItemId
     * @return Response
     * @throws \yii\authclient\InvalidResponseException
     * @throws \yii\httpclient\Exception
     * @inheritdoc
     */
    public function deleteOrderItemLink(int $orderId, int $orderItemId): Response
    {
        $data = [
            'Object' => 'mod_order@GuiOrderDetails',
            'Params' => [
                'gui' => 'AjaxRowEditTableRow',
                'result_id' => 'OrderEditPositionsPane_' . $orderItemId,
            ],
            'action' => 'deleteArticleLink',
            'o_id' => $orderId,
            'order_row_id' => $orderItemId,
            'additional_id' => '',
        ];
        $requestUrl = strtr($this->guiCallUrl, ['{domainHash}' => $this->getDomainHash()]);
        return $this->request($requestUrl, 'POST', $data);
    }

    /**
     * @param int $orderId
     * @param int $orderItemId
     * @param int $itemId
     * @param int $variationId
     * @param int $warehouseId
     * @return Response
     * @throws InvalidConfigException
     * @throws \yii\authclient\InvalidResponseException
     * @throws \yii\httpclient\Exception
     * @inheritdoc
     */
    public function assignOrderItemLink(int $orderId, int $orderItemId, int $itemId, int $variationId, int $warehouseId): Response
    {
        $sessionData = $this->getSessionData();
        $data = [
            'requests' => [
                [
                    '_dataName' => 'OrderItem',
                    '_moduleName' => 'order/item',
                    '_searchParams' => [],
                    '_writeParams' => [],
                    '_validateParams' => [],
                    '_commandStack' => [
                        [
                            'type' => 'write',
                            'command' => 'assignItem',
                        ],
                    ],
                    '_dataArray' => [
                        'orderId' => $orderId,
                        'itemId' => $itemId,
                        'id' => $orderItemId,
                        'itemVariationId' => $variationId,
                        'warehouseId' => $warehouseId,
                    ],
                    '_dataList' => [],
                ],
            ],
            'meta' => [
                'id' => $sessionData['userId'],
                'token' => $sessionData['csrfToken'],
            ],
        ];
        $data = ['request' => Json::encode($data)];
        $requestUrl = strtr($this->apiUiUrl, ['{domainHash}' => $this->getDomainHash()]);
        return $this->request($requestUrl, 'POST', $data);
    }

    /**
     * @param array $orderIds
     * @return array
     * @throws InvalidConfigException
     * @throws \yii\httpclient\Exception
     * @inheritdoc
     */
    public function regenerateOrderInvoice(array $orderIds)
    {
        $data = array_map(static function ($orderId) {
            return [
                'Object' => 'mod_order@GuiOrderDetails',
                'Params' => [
                    'gui' => 'AjaxDocumentsPane',
                    'result_id' => 'DocumentsTabContent_' . $orderId,
                ],
                'gwt_tab_id' => '',
                'presenter_id' => '1',
                'action' => 'invoice_reset',
                'o_id' => $orderId,
                'additional_id' => '',
            ];
        }, $orderIds);
        $requestUrl = strtr($this->guiCallUrl, ['{domainHash}' => $this->getDomainHash()]);
        return $this->batchRequest($requestUrl, 'POST', $data);
    }

    #endregion
}
