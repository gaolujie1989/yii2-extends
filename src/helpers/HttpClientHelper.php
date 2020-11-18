<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\helpers;


use Yii;
use yii\authclient\InvalidResponseException;
use yii\helpers\FileHelper;
use yii\httpclient\Client;
use yii\httpclient\CurlTransport;
use yii\httpclient\Exception;
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
     * @var int
     */
    public static $retry = 1;

    /**
     * @param Request $request
     * @return Response
     * @throws Exception
     * @inheritdoc
     */
    public static function tryRequest(Request $request): Response
    {
        $retry = 0;
        while (true) {
            try {
                return $request->send();
            } catch (Exception $exception) {
                $retry++;
                if ($retry <= static::$retry) {
                    Yii::warning("HttpRequest failed and retry {$retry}... Message: {$exception->getMessage()}");
                } else {
                    throw $exception;
                }
            }
        }
    }

    /**
     * @param Request $request
     * @param array $allowedStatusCodes
     * @return Response
     * @throws Exception
     * @throws InvalidResponseException
     * @inheritdoc
     */
    public static function sendRequest(Request $request, $allowedStatusCodes = []): Response
    {
        $response = static::tryRequest($request);

        if (!$response->getIsOk() && !in_array($response->getStatusCode(), $allowedStatusCodes)) {
            //not append content because content maybe not utf8 encode, it will cause error
            $message = 'Request failed with code: ' . $response->getStatusCode(); // . ', message: ' . $response->getContent();
            throw new InvalidResponseException($response, $message);
        }

        return $response;
    }

    /**
     * @param Request $request
     * @param string $outputFile
     * @param bool $throwException
     * @return bool
     * @throws \Throwable
     * @throws \yii\httpclient\Exception
     * @inheritdoc
     */
    public static function downloadFile(Request $request, string $outputFile, $throwException = true): bool
    {
        $request->client->setTransport(CurlTransport::class);
        $request->setFormat(Client::FORMAT_URLENCODED);

        $outputFile = Yii::getAlias($outputFile);
        FileHelper::createDirectory(dirname($outputFile));
        if (file_exists($outputFile)) {
            unlink($outputFile);
        }

        $file = fopen($outputFile, 'wb');
        try {
            $request->setOutputFile($file)->send();
        } catch (\Throwable $e) {
            fclose($file);
            unlink($outputFile);
            if ($throwException) {
                throw $e;
            }
            return false;
        }
        fclose($file);
        return true;
    }
}