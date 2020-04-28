<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\authclient;

use yii\authclient\BaseClient;
use yii\httpclient\CurlTransport;
use yii\httpclient\Request;
use yii\httpclient\RequestEvent;
use yii\httpclient\Response;
use yii\web\Cookie;
use yii\web\CookieCollection;

/**
 * Class BaseLoginClient
 * @package lujie\extend\authclient
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
abstract class BaseCookieClient extends BaseClient
{
    /**
     * @var string
     */
    public $loginUrl;

    /**
     * @var array
     */
    public $loginData = [];

    /**
     * @var string
     */
    public $username;

    /**
     * @var string
     */
    public $password;

    /**
     * @var CookieCollection
     */
    private $_cookies;

    /**
     * @var string
     */
    public $cacheStorage = CacheStateStorage::class;

    /**
     * @var array
     */
    public $httpClientOptions = [
        'transport' => CurlTransport::class
    ];

    /**
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        if ($this->cacheStorage) {
            $this->setStateStorage($this->cacheStorage);
        }
        if ($this->httpClientOptions) {
            $this->setHttpClient($this->httpClientOptions);
        }
    }

    /**
     * @return CookieCollection
     * @inheritdoc
     */
    public function login(): CookieCollection
    {
        $loginData = array_merge($this->loginData, [
            'username' => $this->username,
            'password' => $this->password,
        ]);

        /** @var Response $response */
        $response = $this->createRequest()
            ->setUrl($this->loginUrl)
            ->setMethod('POST')
            ->setData($loginData)
            ->send();

        $cookies = $response->getCookies();
        $this->setCookies($cookies);
        return $cookies;
    }

    /**
     * @param CookieCollection $cookies
     * @inheritdoc
     */
    public function setCookies(CookieCollection $cookies): void
    {
        $this->_cookies = $cookies;
        $this->setState('cookies', $cookies);
    }

    /**
     * @return CookieCollection
     * @inheritdoc
     */
    public function getCookies(): CookieCollection
    {
        if (empty($this->_cookies)) {
            $this->_cookies = $this->getState('cookies') ?: $this->login();
            $now = time();
            /** @var Cookie $cookie */
            foreach ($this->_cookies as $cookie) {
                if ($cookie->expire && $cookie->expire <= $now) {
                    $this->_cookies = $this->login();
                }
            }
        }
        return $this->_cookies;
    }

    /**
     * @return Request
     * @inheritdoc
     */
    public function createAuthRequest(): Request
    {
        $request = $this->createRequest();
        $request->on(Request::EVENT_BEFORE_SEND, [$this, 'beforeAuthRequestSend']);
        return $request;
    }

    /**
     * @param RequestEvent $event
     * @inheritdoc
     */
    public function beforeAuthRequestSend(RequestEvent $event): void
    {
        $cookies = $this->getCookies();
        $this->applyCookiesToRequest($event->request, $cookies);
    }

    /**
     * @param Request $request
     * @param CookieCollection $cookies
     * @inheritdoc
     */
    public function applyCookiesToRequest(Request $request, CookieCollection $cookies): void
    {
        $request->addCookies($cookies->toArray());
    }

    /**
     * @param string $callSubUrl
     * @param string $method
     * @param array|string $data
     * @param array $headers
     * @return Response
     * @throws \yii\httpclient\Exception
     * @inheritdoc
     */
    public function request(string $callSubUrl, string $method = 'GET', $data = [], $headers = []): Response
    {
        $request = $this->createAuthRequest()
            ->setMethod($method)
            ->setUrl($callSubUrl)
            ->addHeaders($headers);

        if (!empty($data)) {
            if (is_array($data)) {
                $request->setData($data);
            } else {
                $request->setContent($data);
            }
        }

        return $request->send();
    }
}
