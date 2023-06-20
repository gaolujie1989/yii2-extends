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
use yii\httpclient\RequestEvent;

/**
 * Class ResponseLogger
 * @package lujie\extend\httpclient
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class HttpResponseLogger extends BaseObject implements BootstrapInterface
{
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
        $headers = $response->getHeaders()->toArray();
        foreach ($headers as $key => $header) {
            $headers[$key] = $key . ': ' . implode('; ', $header);
        }
        $logContent = $this->createResponseLogContent($request->getMethod(), $request->getFullUrl(), $headers, print_r($response->getContent(), true));
        $fileName = strtr($request->getFullUrl(), ["://" => "_", '.' => '_', '/' => '_', '?' => '_', '=' => '_']);
        $fileName =  date('YmdHis') . '_' . $request->getMethod() . '_' . $fileName . '.log';
        $path = rtrim(Yii::getAlias($this->logPath), '/') . '/';
        FileHelper::createDirectory($path);
        file_put_contents($path . $fileName, $logContent);
    }

    /**
     * @param string $method
     * @param string $url
     * @param array $headers
     * @param string|null $content
     * @return string
     * @inheritdoc
     */
    public function createResponseLogContent(string $method, string $url, array $headers, ?string $content): string
    {
        $token = strtoupper($method) . ' ' . $url;
        if (!empty($headers)) {
            $token .= "\n" . implode("\n", $headers);
        }
        if ($content !== null) {
            $token .= "\n\n" . $content;
        }
        return $token;
    }
}
