<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\plentyMarkets;

use lujie\extend\helpers\ObjectHelper;
use yii\base\BaseObject;
use yii\base\InvalidConfigException;
use yii\di\Instance;
use yii\httpclient\Client;
use yii\httpclient\CurlTransport;

/**
 * Class PlentyMarketsDynamicExport
 * @package lujie\plentyMarkets
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class PlentyMarketsAdminClient extends BaseObject
{
    /**
     * @var PlentyMarketsAdminLogin
     */
    public $login = [];

    /**
     * @var array
     */
    public $clientConfig = [
        'transport' => CurlTransport::class,
    ];

    /**
     * @var string
     */
    public $guiCallUrl = 'https://{domainHash}.plentymarkets-cloud-de.com/plenty/admin/gui_call.php?';

    /**
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        $this->login = Instance::ensure($this->login, PlentyMarketsAdminLogin::class);
        $this->login->clientConfig = $this->clientConfig;
    }

    /**
     * @param array $query
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
        $domainHash = $this->login->getDomainHash();
        $requestUrl = strtr($this->guiCallUrl, ['{domainHash}' => $domainHash]) . http_build_query($query);
        $header = [
            'Cookie' => strtr('SID_PLENTY_ADMIN_{plentyId}={adminSession}', [
               '{plentyId}' => $this->login->plentyId,
               '{adminSession}' => $this->login->getAdminSession(),
            ]),
        ];
        /** @var Client $client */
        $client = ObjectHelper::create($this->clientConfig, Client::class);
        $response = $client->get($requestUrl, null, $header)->send();
        return $response->getContent();
    }
}
