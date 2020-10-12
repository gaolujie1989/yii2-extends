<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\helpers;


use yii\authclient\InvalidResponseException;
use yii\httpclient\CurlTransport;
use yii\httpclient\Request;
use yii\httpclient\Response;

/**
 * Class HttpClientHelper
 * @package lujie\extend\helpers
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class HttpClientHelper
{
    /**
     * @param Request $request
     * @return Response
     * @throws InvalidResponseException
     * @throws \yii\httpclient\Exception
     * @inheritdoc
     */
    public function sendRequest(Request $request): Response
    {
        $response = $request->send();

        if (!$response->getIsOk()) {
            $message = 'Request failed with code: ' . $response->getStatusCode() . ', message: ' . $response->getContent();
            throw new InvalidResponseException($response, $message);
        }

        return $response;
    }

    /**
     * @param Request $request
     * @param string $outputFile
     * @throws \Throwable
     * @throws \yii\httpclient\Exception
     * @inheritdoc
     */
    public function downloadFile(Request $request, string $outputFile)
    {
        $request->client->setTransport(CurlTransport::class);
        if (file_exists($outputFile)) {
            unlink($outputFile);
        }
        $file = fopen($outputFile, 'wb');
        try {
            $request->setOutputFile($file)->send();
        } catch (\Throwable $e) {
            fclose($file);
            unlink($outputFile);
            throw $e;
        }
        fclose($file);
    }
}