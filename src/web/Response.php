<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\workerman\web;

use lujie\workerman\WebResponse;
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
     * @var WebResponse
     */
    private $workermanResponse;

    /**
     * @return WebResponse
     * @inheritdoc
     */
    public function getWorkermanResponse(): WebResponse
    {
        return $this->workermanResponse;
    }

    /**
     * Sends the response to the client.
     * @return WebResponse
     * @inheritdoc
     */
    public function send(): void
    {
        $this->workermanResponse = $this->workermanResponse ?: new WebResponse();
        parent::send();
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
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function sendHeaders(): void
    {
        foreach ($this->getHeaders() as $name => $values) {
            $name = str_replace(' ', '-', ucwords(str_replace('-', ' ', $name)));
            $this->workermanResponse->header($name, $values);
        }
        $statusCode = $this->getStatusCode();
        $this->workermanResponse->withStatus($statusCode);
        $this->sendCookies();
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
        } else {
            $this->workermanResponse->withStream($this->stream);
        }
    }
}