<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\authclient;

use yii\authclient\BaseClient;
use yii\authclient\OAuthToken;
use yii\base\Exception;
use yii\httpclient\Request;
use yii\httpclient\RequestEvent;
use yii\httpclient\Response;
use yii\web\CookieCollection;

/**
 * Class BaseLoginClient
 * @package lujie\extend\authclient
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
abstract class BaseCookieClient extends BaseClient
{
    public $loginUrl;

    public $loginData = [];

    public $username;

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
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        if ($this->cacheStorage) {
            $this->setStateStorage($this->cacheStorage);
        }
    }

    /**
     * @inheritdoc
     */
    public function login(): void
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
        $this->setCookies($response->getCookies());
    }

    /**
     * @param array|CookieCollection $cookies
     * @inheritdoc
     */
    public function setCookies($cookies): void
    {
        $this->_cookies = $cookies;
        $this->setState('cookies', $cookies);
    }

    /**
     * @param array|CookieCollection $cookies
     * @inheritdoc
     */
    public function getCookies()
    {
        if (empty($this->_cookies)) {
            $this->_cookies = $this->getState('cookies');
        }
        return $this->_cookies;
    }

    /**
     * @return Request
     * @inheritdoc
     */
    public function createApiRequest(): Request
    {
        $request = $this->createRequest();
        $request->on(Request::EVENT_BEFORE_SEND, [$this, 'beforeApiRequestSend']);
        return $request;
    }

    /**
     * @param $event
     * @throws Exception
     * @inheritdoc
     */
    public function beforeApiRequestSend(RequestEvent $event): void
    {
        $cookies = $this->getCookies();
        if (empty($cookies)) {
            throw new Exception('Invalid cookies.');
        }
        $this->applyCookiesToRequest($event->request, $cookies);
    }

    /**
     * @param Request $request
     * @param $cookies
     * @inheritdoc
     */
    public function applyCookiesToRequest(Request $request, $cookies): void
    {
        $request->addCookies($cookies);
    }
}
