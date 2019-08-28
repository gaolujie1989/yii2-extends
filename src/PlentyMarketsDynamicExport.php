<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\plentyMarkets;

use Iterator;
use lujie\extend\helpers\ObjectHelper;
use Yii;
use yii\base\BaseObject;
use yii\base\InvalidConfigException;
use yii\helpers\FileHelper;
use yii\httpclient\Client;

/**
 * Class PlentyMarketsDynamicExport
 * @package lujie\plentyMarkets
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class PlentyMarketsDynamicExport extends BaseObject
{
    /**
     * @var array
     */
    public $clientConfig = [];

    /**
     * @var string
     */
    public $url = 'https://{domainHash}.plentymarkets-cloud-de.com/plenty/admin/gui_call.php?';

    /**
     * @var array
     */
    public $query = [
        'Object' => 'mod_export@GuiDynamicFieldExportView2',
        'Params' => [
            'gui' => 'AjaxExportData',
        ],
        'gwt_tab_id' => '',
        'presenter_id' => '',
        'action' => 'ExportDataFormat',
        'formatDynamicUserName' => 'OrderCompleteAllField',
        'offset' => '0',
        'rowCount' => '6000',
        'deletedOrderOption' => '0',
        'stockBarcodeOption' => '1',
    ];

    public $header = [
        'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3',
        'Accept-Encoding' => 'gzip, deflate, br',
        'Accept-Language' => 'zh-CN,zh;q=0.9,en;q=0.8,en-US;q=0.7,de;q=0.6,zh-TW;q=0.5,ja;q=0.4',
        'Cache-Control' => 'no-cache',
        'Connection' => 'keep-alive',
//        'Cookie' => null,
//        'Host' => '{domainHash}.plentymarkets-cloud-de.com',
        'Pragma' => 'no-cache',
        'Sec-Fetch-Mode' => 'navigate',
        'Sec-Fetch-Site' => 'none',
        'Upgrade-Insecure-Requests' => 1,
        'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.100 Safari/537.36'
    ];

    /**
     * @var string like a0cf0404e54219ccc287c5bceff4da4033f7d935(<-domainHash).plentymarkets-cloud-de.com
     */
    public $domainHash = '';

    /**
     * @var string
     */
    public $cookie = '';

    /**
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        if (empty($this->domainHash) || empty($this->cookie)) {
            throw new InvalidConfigException('The property `domainHash` and `cookie` must be set');
        }
    }

    /**
     * @param array $query
     * @return string
     * @throws InvalidConfigException
     * @throws \yii\httpclient\Exception
     * @inheritdoc
     */
    public function export($query = []): string
    {
        $query = array_merge($this->query, $query);
        $requestUrl = strtr($this->url, ['{domainHash}' => $this->domainHash]) . http_build_query($query);
        $header = array_merge($this->header, [
            'Host' => $this->domainHash . '.plentymarkets-cloud-de.com',
            'Cookie' => $this->cookie,
        ]);
        /** @var Client $client */
        $client = ObjectHelper::create($this->clientConfig, Client::class);
        $response = $client->get($requestUrl, null, $header)->send();
        return $response->getContent();
    }

    /**
     * @param $downloadPath
     * @param array $query
     * @return Iterator
     * @throws InvalidConfigException
     * @throws \yii\base\Exception
     * @throws \yii\httpclient\Exception
     * @inheritdoc
     */
    public function downloadExports($downloadPath, $query = []): Iterator
    {
        $downloadPath = Yii::getAlias($downloadPath);
        FileHelper::createDirectory($downloadPath);
        $rowCount = $query['rowCount'] ?? $this->query['rowCount'];
        for ($i = 0; $i < 100; $i++) {
            $fileContent = $this->export($query);
            $file = "{$downloadPath}/export_{$i}.csv";
            file_put_contents($file, $fileContent);
            yield $file;

            $fileRowCount = count(file($file));
            if ($fileRowCount < $rowCount) {
                break;
            }
        }
    }
}
