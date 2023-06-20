<?php
/**
 * @copyright Copyright (c) 2023
 */

namespace lujie\extend\httpclient;

use Yii;
use yii\base\BaseObject;
use yii\base\BootstrapInterface;
use yii\base\Event;
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
     * @var int
     */
    public $contentLoggingMaxSize = 5000;

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
        $token = $this->createResponseLogToken($request->getMethod(), $request->getFullUrl(), $headers, print_r($response->getContent(), true));
        Yii::info($token, __METHOD__);
    }

    /**
     * @param string $method
     * @param string $url
     * @param array $headers
     * @param string|null $content
     * @return string
     * @inheritdoc
     */
    public function createResponseLogToken(string $method, string $url, array $headers, ?string $content): string
    {
        $token = strtoupper($method) . ' ' . $url;
        if (!empty($headers)) {
            $token .= "\n" . implode("\n", $headers);
        }
        if ($content !== null) {
            $token .= "\n\n" . StringHelper::truncate($content, $this->contentLoggingMaxSize);
        }
        return $token;
    }
}
