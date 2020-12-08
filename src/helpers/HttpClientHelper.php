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
     * @param int|null $retry
     * @return Response
     * @throws Exception
     * @inheritdoc
     */
    public static function tryRequest(Request $request, ?int $retry = null): Response
    {
        $retry = $retry ?: static::$retry;
        $try = 0;
        while (true) {
            try {
                return $request->send();
            } catch (Exception $exception) {
                $try++;
                if ($try <= $retry) {
                    Yii::warning("Http {$request->method} request to {$request->fullUrl} failed and retry {$try}... Message: {$exception->getMessage()}");
                } else {
                    throw $exception;
                }
            }
        }
    }

    /**
     * @param Request $request
     * @param array $allowedStatusCodes
     * @param int|null $retry
     * @return Response
     * @throws Exception
     * @throws InvalidResponseException
     * @inheritdoc
     */
    public static function sendRequest(Request $request, array $allowedStatusCodes = [], ?int $retry = null): Response
    {
        Yii::info("Send {$request->method} request to {$request->fullUrl}", __METHOD__);
        $response = static::tryRequest($request, $retry);

        if (!$response->getIsOk() && !static::isAllowedStatusCodes($response->getStatusCode(), $allowedStatusCodes)) {
            $message = "Request failed with code: {$response->getStatusCode()}\nResponse: {$response->toString()}\nRequest: {$request->toString()}";
            throw new InvalidResponseException($response, $message);
        }

        return $response;
    }

    /**
     * @param string|int $statusCode
     * @param array $allowedStatusCodes
     * @return bool
     * @inheritdoc
     */
    public static function isAllowedStatusCodes($statusCode, array $allowedStatusCodes = []): bool
    {
        if (in_array($statusCode, $allowedStatusCodes)) {
            return true;
        }
        foreach ($allowedStatusCodes as $allowedStatusCode) {
            if (ValueHelper::isMatch($statusCode, $allowedStatusCode)) {
                return true;
            }
        }
        return false;
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
        Yii::info("Download file from {$request->fullUrl} save to {$outputFile}", __METHOD__);
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