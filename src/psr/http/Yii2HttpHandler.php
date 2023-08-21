<?php
/**
 * @copyright Copyright (c) 2023
 */

namespace lujie\extend\psr\http;

use GuzzleHttp\Promise\Create;
use GuzzleHttp\Promise\PromiseInterface;
use lujie\extend\helpers\HttpClientHelper;
use Nyholm\Psr7\Response as Psr7Response;
use Psr\Http\Message\RequestInterface;
use yii\authclient\BaseClient;
use yii\base\InvalidConfigException;
use yii\base\NotSupportedException;
use yii\httpclient\Request;
use yii\httpclient\Response;

/**
 * Class YiiHttpHandler
 * @package lujie\extend\httpclient
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class Yii2HttpHandler extends BaseClient
{
    /**
     * @var ?Request
     */
    private $lastRequest;

    /**
     * @var ?Response
     */
    private $lastResponse;

    /**
     * @throws NotSupportedException
     * @inheritdoc
     */
    protected function initUserAttributes(): void
    {
        throw new NotSupportedException('Not supported.');
    }

    /**
     * @return Request|null
     */
    public function getLastRequest(): ?Request
    {
        return $this->lastRequest;
    }

    /**
     * @return Response|null
     */
    public function getLastResponse(): Response|null
    {
        return $this->lastResponse;
    }

    /**
     * @param RequestInterface $request
     * @param array $options
     * @return PromiseInterface
     * @throws InvalidConfigException
     * @throws \yii\authclient\InvalidResponseException
     * @throws \yii\httpclient\Exception
     * @inheritdoc
     */
    public function sendAsync(RequestInterface $request, array $options = []): PromiseInterface
    {
        $httpRequest = $this->createRequest()
            ->setMethod($request->getMethod())
            ->setUrl((string)$request->getUri())
            ->setHeaders($request->getHeaders())
            ->setOptions($options);

        $contents = $request->getBody()->getContents();
        if ($contents) {
            $httpRequest->setContent($contents);
        }

        $httpResponse = HttpClientHelper::sendRequest($httpRequest);
        $this->lastRequest = $httpRequest;
        $this->lastResponse = $httpResponse;
        return Create::promiseFor(new Psr7Response($httpResponse->getStatusCode(), $httpResponse->getHeaders()->toArray(), $httpResponse->getContent()));
    }
}
