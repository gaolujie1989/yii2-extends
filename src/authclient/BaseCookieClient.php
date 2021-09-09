<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\authclient;

use lujie\extend\helpers\HttpClientHelper;
use Yii;
use yii\authclient\BaseClient;
use yii\authclient\CacheStateStorage;
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
     * @var string
     */
    public $username;

    /**
     * @var string
     */
    public $password;

    /**
     * @var int
     */
    public $expireDuration = 1800;

    /**
     * @var ?CookieCollection
     */
    private $cookies;

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

    #region Base Auth

    /**
     * @return string
     * @inheritdoc
     */
    public function getId(): string
    {
        return parent::getId() . '_' . $this->username;
    }

    /**
     * @param array $params
     * @return CookieCollection
     * @throws \yii\httpclient\Exception
     * @inheritdoc
     */
    public function login(array $params = []): CookieCollection
    {
        if (empty($params)) {
            $params = [
                'username' => $this->username,
                'password' => $this->password,
            ];
        }

        Yii::info("Login to {$this->loginUrl} with username {$this->username}", __METHOD__);
        $response = $this->createRequest()
            ->setUrl($this->loginUrl)
            ->setMethod('POST')
            ->setData($params)
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
        $defaultExpireAt = time() + $this->expireDuration;
        foreach ($cookies as $cookie) {
            if (empty($cookie->expire)) {
                $cookie->expire = $defaultExpireAt;
            }
        }
        Yii::debug("Set Cookies", __METHOD__);
        $this->cookies = $cookies;
        $this->setState('cookies', $cookies);
    }

    /**
     * @return CookieCollection
     * @throws \yii\httpclient\Exception
     * @inheritdoc
     */
    public function getCookies(): CookieCollection
    {
        if (empty($this->cookies)) {
            Yii::debug("Get Cookies", __METHOD__);
            $this->cookies = $this->getState('cookies') ?: $this->login();
            $now = time();
            /** @var Cookie $cookie */
            foreach ($this->cookies as $cookie) {
                $expire = is_numeric($cookie->expire) ? $cookie->expire : strtotime($cookie->expire);
                if ($expire <= $now) {
                    Yii::debug("Cookies expired, login again", __METHOD__);
                    $this->cookies = $this->login();
                    break;
                }
            }
        }
        return $this->cookies;
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
     * @throws \yii\httpclient\Exception
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
        Yii::debug('AddCookies to request', __METHOD__);
        $request->addCookies($cookies->toArray());
    }

    #endregion

    /**
     * @param string $callSubUrl
     * @param string $method
     * @param array|string $data
     * @param array $headers
     * @return Response
     * @throws \yii\authclient\InvalidResponseException
     * @throws \yii\httpclient\Exception
     * @inheritdoc
     */
    public function request(string $callSubUrl, string $method = 'GET', $data = [], array $headers = []): Response
    {
        $request = $this->createReadyRequest($callSubUrl, $method, $data, $headers);
        return HttpClientHelper::sendRequest($request);
    }

    /**
     * @param string $callSubUrl
     * @param string $method
     * @param array $data
     * @param array $headers
     * @return array
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\httpclient\Exception
     * @inheritdoc
     */
    public function batchRequest(string $callSubUrl, string $method = 'GET', array $data = [], array $headers = []): array
    {
        $requests = [];
        foreach ($data as $item) {
            $requests[] = $this->createReadyRequest($callSubUrl, $method, $item, $headers);
        }
        return $this->httpClient->batchSend($requests);
    }

    /**
     * @param string $callSubUrl
     * @param string $method
     * @param array|string $data
     * @param array $headers
     * @return Request
     * @inheritdoc
     */
    public function createReadyRequest(string $callSubUrl, string $method = 'GET', $data = [], array $headers = []): Request
    {
        $request = $this->createAuthRequest()
            ->setMethod($method)
            ->setUrl($callSubUrl)
            ->addHeaders($headers);

        if (!empty($data)) {
            if (is_array($data)) {
                $request->setData($data);
            } else {
                $request->setContent((string)$data);
            }
        }
        return $request;
    }
}
