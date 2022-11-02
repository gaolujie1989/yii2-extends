<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\workerman\web;

use Workerman\Protocols\Http\Response as WorkermanResponse;
use Yii;
use yii\base\InvalidConfigException;
use yii\web\Cookie;

/**
 * Class Response
 * @package lujie\workerman\web
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class Response extends \yii\web\Response
{
    /**
     * @var WorkermanResponse
     */
    private $workermanResponse;

    /**
     * @return WorkermanResponse
     * @inheritdoc
     */
    public function getWorkermanResponse(): WorkermanResponse
    {
        return $this->workermanResponse;
    }

    /**
     * Sends the response to the client.
     * @return WorkermanResponse
     * @inheritdoc
     */
    public function send(): WorkermanResponse
    {
        $this->workermanResponse = new WorkermanResponse();
        parent::send();
        return $this->workermanResponse;
    }

    /**
     * @inheritdoc
     */
    public function clear(): void
    {
        parent::clear();
        $this->workermanResponse = null;
    }

    /**
     * @inheritdoc
     */
    public function sendHeaders(): void
    {
        foreach ($this->getHeaders() as $name => $values) {
            $name = str_replace(' ', '-', ucwords(str_replace('-', ' ', $name)));
            $this->workermanResponse->withHeader($name, reset($value));
        }
        $statusCode = $this->getStatusCode();
        $this->workermanResponse->withStatus($statusCode);
    }

    /**
     * @throws InvalidConfigException
     * @inheritdoc
     */
    protected function sendCookies(): void
    {
        $request = Yii::$app->getRequest();
        if ($request->enableCookieValidation) {
            if ($request->cookieValidationKey === '') {
                throw new InvalidConfigException($request::class . '::cookieValidationKey must be configured with a secret key.');
            }
            $validationKey = $request->cookieValidationKey;
        }
        /** @var Cookie $cookie */
        foreach ($this->getCookies() as $cookie) {
            $value = $cookie->value;
            if ($cookie->expire !== 1 && isset($validationKey)) {
                $value = Yii::$app->getSecurity()->hashData(serialize([$cookie->name, $value]), $validationKey);
            }
            $this->workermanResponse->cookie(
                $cookie->name,
                $value,
                $cookie->expire,
                $cookie->path,
                $cookie->domain,
                $cookie->secure,
                $cookie->httpOnly,
                !empty($cookie->sameSite) ? $cookie->sameSite : null,
            );
        }
    }

    /**
     * Sends the response content to the client.
     */
    protected function sendContent(): void
    {
        if ($this->stream === null) {
            $this->workermanResponse->withBody($this->content);
            return;
        }

        if (is_callable($this->stream)) {
            $data = call_user_func($this->stream);
            foreach ($data as $datum) {
                echo $datum;
                flush();
            }
            return;
        }

        $chunkSize = 8 * 1024 * 1024; // 8MB per chunk

        if (is_array($this->stream)) {
            list($handle, $begin, $end) = $this->stream;

            // only seek if stream is seekable
            if ($this->isSeekable($handle)) {
                fseek($handle, $begin);
            }

            while (!feof($handle) && ($pos = ftell($handle)) <= $end) {
                if ($pos + $chunkSize > $end) {
                    $chunkSize = $end - $pos + 1;
                }
                echo fread($handle, $chunkSize);
                flush(); // Free up memory. Otherwise large files will trigger PHP's memory limit.
            }
            fclose($handle);
        } else {
            while (!feof($this->stream)) {
                echo fread($this->stream, $chunkSize);
                flush();
            }
            fclose($this->stream);
        }
    }

    /**
     * @param string $filePath
     * @param null $attachmentName
     * @param array $options
     * @return $this
     * @inheritdoc
     */
    public function sendFile($filePath, $attachmentName = null, $options = []): Response
    {
        $this->workermanResponse = new WorkermanResponse();
        $this->workermanResponse->withFile($filePath);
        return $this;
    }
}