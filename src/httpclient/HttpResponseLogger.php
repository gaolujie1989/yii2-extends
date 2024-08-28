<?php
/**
 * @copyright Copyright (c) 2023
 */

namespace lujie\extend\httpclient;

use Yii;
use yii\base\BaseObject;
use yii\base\BootstrapInterface;
use yii\base\Event;
use yii\helpers\FileHelper;
use yii\helpers\StringHelper;
use yii\httpclient\Client;
use yii\httpclient\Request;
use yii\httpclient\RequestEvent;
use yii\web\HeaderCollection;

/**
 * Class HttpResponseLogger
 * @package lujie\extend\httpclient
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class HttpResponseLogger extends BaseObject implements BootstrapInterface
{
    /**
     * @var int
     */
    public $contentLoggingMaxSize = 2000;

    /**
     * @var string
     */
    public $logPath = '@runtime/requests/';

    /**
     * @param $app
     * @inheritdoc
     */
    public function bootstrap($app): void
    {
        Event::on(Client::class, Client::EVENT_AFTER_SEND, [$this, 'logResponse']);
    }

    /**
     * @param RequestEvent $requestEvent
     * @throws \yii\base\Exception
     * @inheritdoc
     */
    public function logResponse(RequestEvent $requestEvent): void
    {
        $request = $requestEvent->request;
        $response = $requestEvent->response;
        $logContent = $this->createLogContent($request->getMethod(), $request->getFullUrl(), $this->getHeaders($request->getHeaders()), print_r($request->getContent(), true))
            . "\n\n"
            . $this->createLogContent('', '', $this->getHeaders($response->getHeaders()), print_r($response->getContent(), true));
        if (YII_DEBUG) {
            $this->saveRequestFile($request, $logContent);
        } else {
            Yii::info($logContent, Client::class);
        }
    }

    /**
     * @param Request $request
     * @param string $logContent
     * @throws \yii\base\Exception
     * @inheritdoc
     */
    public function saveRequestFile(Request $request, string $logContent): void
    {
        $fileName = substr(strtr($request->getFullUrl(), ["://" => "_", '.' => '_', '/' => '_', '?' => '_', '=' => '_']), 0, 200);
        $fileName = date('YmdHis') . '_' . $request->getMethod() . '_' . $fileName . '.log';
        $path = rtrim(Yii::getAlias($this->logPath), '/') . '/';
        FileHelper::createDirectory($path);
        file_put_contents($path . $fileName, $logContent);
    }

    /**
     * @param HeaderCollection $headerCollection
     * @return array
     * @inheritdoc
     */
    public function getHeaders(HeaderCollection $headerCollection): array
    {
        $headers = [];
        foreach ($headerCollection as $key => $header) {
            $headers[$key] = $key . ': ' . implode('; ', $header);
        }
        return $headers;
    }

    /**
     * @param string $method
     * @param string $url
     * @param array $headers
     * @param string|null $content
     * @return string
     * @inheritdoc
     */
    public function createLogContent(string $method, string $url, array $headers, ?string $content): string
    {
        $token = trim(strtoupper($method) . ' ' . $url);
        if (!empty($headers)) {
            $token .= "\n" . implode("\n", $headers);
        }
        if ($content !== null) {
            $token .= "\n\n" . (YII_DEBUG ? $content : StringHelper::truncate($content, $this->contentLoggingMaxSize));
        }
        return $token;
    }
}
