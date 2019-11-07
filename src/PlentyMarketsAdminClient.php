<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\plentyMarkets;

use lujie\extend\authclient\BaseCookieClient;
use yii\base\InvalidConfigException;
use yii\helpers\Json;
use yii\web\CookieCollection;

/**
 * Class PlentyMarketsDynamicExport
 * @package lujie\plentyMarkets
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class PlentyMarketsAdminClient extends BaseCookieClient
{
    public $plentyId;

    public $loginUrl = 'https://plentymarkets-cloud-de.com/';

    /**
     * @var string
     */
    public $guiCallUrl = 'https://{domainHash}.plentymarkets-cloud-de.com/plenty/admin/gui_call.php?';

    protected function initUserAttributes()
    {
    }

    /**
     * @return CookieCollection
     * @throws InvalidConfigException
     * @throws \yii\httpclient\Exception
     * @inheritdoc
     */
    public function login(): CookieCollection
    {
        $this->loginData = [
            'safemode' => 0,
            'terraRoute' => '/' . $this->plentyId,
            'queryParams' => '',
            'language' => '',
            'pid' => $this->plentyId,
        ];
        parent::login();
        $this->setSessionID();
        return $this->getCookies();
    }

    /**
     * @return mixed
     * @throws InvalidConfigException
     * @throws \yii\httpclient\Exception
     * @inheritdoc
     */
    protected function getDomainHash(): ?string
    {
        $cookies = $this->getCookies();
        return $cookies ? $cookies->getValue('domainHash') : null;
    }

    /**
     * @return string
     * @throws InvalidConfigException
     * @inheritdoc
     */
    protected function getAuthorization(): string
    {
        $cookies = $this->getCookies();
        if ($cookies !== null) {
            foreach ($cookies as $cookie) {
                if (strpos($cookie->name, 'at') === 0 && strlen($cookie->name) === 7) {
                    return $cookie->value;
                }
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
            'Referer' => strtr('https://{domainHash}.plentymarkets-cloud-de.com/plenty/gwt/productive/2a28ee69/admin.html',
                ['{domainHash}' => $this->getDomainHash()]),
        ];
        $sessionUrl = strtr('https://{domainHash}.plentymarkets-cloud-de.com/plenty/api/ui.php', ['{domainHash}' => $this->getDomainHash()]);
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
    }

    /**
     * @param string $name
     * @param int $offset
     * @param int $rowCount
     * @return string
     * @throws InvalidConfigException
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
        $requestUrl = strtr($this->guiCallUrl, ['{domainHash}' => $this->getDomainHash()]) . http_build_query($query);
        $response = $this->request($requestUrl);
        return $response->content;
    }
}
