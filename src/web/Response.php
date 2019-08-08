<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\workerman\web;

use Workerman\Protocols\Http;
use Yii;
use yii\base\InvalidConfigException;

/**
 * Class Response
 * @package lujie\workerman\web
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class Response extends \yii\web\Response
{
    /**
     * @throws InvalidConfigException
     * @inheritdoc
     */
    protected function sendHeaders(): void
    {
        foreach ($this->getHeaders() as $name => $values) {
            $name = str_replace(' ', '-', ucwords(str_replace('-', ' ', $name)));
            // set replace for first occurrence of header but false afterwards to allow multiple
            $replace = true;
            foreach ($values as $value) {
                Http::header("$name: $value", $replace);
                $replace = false;
            }
        }
        $statusCode = $this->getStatusCode();
        Http::header("HTTP/{$this->version} {$statusCode} {$this->statusText}");
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
                throw new InvalidConfigException(get_class($request) . '::cookieValidationKey must be configured with a secret key.');
            }
            $validationKey = $request->cookieValidationKey;
        }
        foreach ($this->getCookies() as $cookie) {
            $value = $cookie->value;
            if ($cookie->expire !== 1 && isset($validationKey)) {
                $value = Yii::$app->getSecurity()->hashData(serialize([$cookie->name, $value]), $validationKey);
            }
            Http::setcookie($cookie->name, $value, $cookie->expire, $cookie->path, $cookie->domain, $cookie->secure, $cookie->httpOnly);
        }
    }
}
