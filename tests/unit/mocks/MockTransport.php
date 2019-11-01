<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace lujie\extend\tests\unit\mocks;

use yii\httpclient\Exception;
use yii\httpclient\Request;
use yii\httpclient\Response;
use yii\httpclient\Transport;

/**
 * Class MockTransport
 *
 * copy from yii MockTransport, fix missing events
 *
 * @package lujie\extend\tests\unit\mocks
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
final class MockTransport extends Transport
{
    /**
     * @var Request[]
     */
    private $requests = [];
    /**
     * @var Response[]
     */
    private $responses = [];


    /**
     * @param Response $response
     */
    public function appendResponse(Response $response)
    {
        $this->responses[] = $response;
    }

    /**
     * @return Request[]
     */
    public function flushRequests()
    {
        $requests = $this->requests;
        $this->requests = [];

        return $requests;
    }

    /**
     * {@inheritdoc}
     */
    public function send($request)
    {
        $request->beforeSend();

        if (empty($this->responses)) {
            throw new Exception('No Response available');
        }

        $nextResponse = array_shift($this->responses);
        if (null === $nextResponse->client) {
            $nextResponse->client = $request->client;
        }

        $this->requests[] = $request;

        $request->afterSend($nextResponse);

        return $nextResponse;
    }
}
