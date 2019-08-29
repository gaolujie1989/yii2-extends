<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\plentyMarkets;

use lujie\extend\helpers\ObjectHelper;
use yii\base\BaseObject;
use yii\base\InvalidConfigException;
use yii\httpclient\Client;
use yii\httpclient\CurlTransport;
use yii\httpclient\Response;
use yii\web\Cookie;

/**
 * Class PlentyMarketsDynamicExport
 * @package lujie\plentyMarkets
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class PlentyMarketsAdminLogin extends BaseObject
{
    public $plentyId;

    public $username;

    public $password;

    /**
     * @var array
     */
    public $clientConfig = [
        'transport' => CurlTransport::class,
    ];

    protected $loginUrl = 'https://plentymarkets-cloud-de.com/';

    protected $sessionUrl = 'https://{domainHash}.plentymarkets-cloud-de.com/plenty/api/ui.php';

    /**
     * @var Response
     */
    protected $loginResponse;

    /**
     * @var Response
     */
    protected $sessionResponse;

    protected $domainHash;

    protected $authorization;

    protected $plentySession;

    /**
     * @return mixed
     * @throws InvalidConfigException
     * @throws \yii\httpclient\Exception
     * @inheritdoc
     */
    public function getDomainHash(): string
    {
        if (!$this->domainHash) {
            $cookies = $this->getLoginResponse()->getCookies();
            $this->domainHash = $cookies->getValue('domainHash');
        }
        return $this->domainHash;
    }

    /**
     * @return mixed
     * @throws InvalidConfigException
     * @throws \yii\httpclient\Exception
     * @inheritdoc
     */
    public function getAuthorization(): string
    {
        if (!$this->authorization) {
            $cookies = $this->getLoginResponse()->getCookies();
            /** @var Cookie $cookie */
            foreach ($cookies as $cookie) {
                if (strpos($cookie->name, 'at') === 0 && strlen($cookie->name) === 7) {
                    $this->authorization = $cookie->value;
                }
            }
            if (empty($this->authorization)) {
                throw new InvalidConfigException('Incorrect username or password');
            }
        }
        return $this->authorization;
    }

    /**
     * @return Response
     * @throws InvalidConfigException
     * @throws \yii\httpclient\Exception
     * @inheritdoc
     */
    protected function getLoginResponse(): Response
    {
        if (!$this->loginResponse) {
            $loginData = [
                'safemode' => 0,
                'terraRoute' => '/' . $this->plentyId,
                'queryParams' => '',
                'language' => '',
                'pid' => $this->plentyId,
                'username' => $this->username,
                'password' => $this->password,
            ];
            /** @var Client $client */
            $client = ObjectHelper::create($this->clientConfig, Client::class);
            $this->loginResponse = $client->post($this->loginUrl, $loginData)->send();
        }
        return $this->loginResponse;
    }

    /**
     * @return string
     * @throws InvalidConfigException
     * @throws \yii\httpclient\Exception
     * @inheritdoc
     */
    public function getAdminSession(): string
    {
        if (!$this->plentySession) {
            $cookies = $this->getSessionResponse()->getCookies();
            $this->plentySession = $cookies->getValue('SID_PLENTY_ADMIN_' . $this->plentyId);
        }
        return $this->plentySession;
    }

    /**
     * @return Response
     * @throws InvalidConfigException
     * @throws \yii\httpclient\Exception
     * @inheritdoc
     */
    protected function getSessionResponse(): Response
    {
        if (!$this->sessionResponse) {
            $sessionRequestData = [
                'requests' => [
                    [
                        '_dataName' => 'PlentyMarketsLogin',
                        '_moduleName' => 'user/login',
                        '_searchParams' => [],
                        '_writeParams' => [],
                        '_validateParams' => [],
                        '_commandStack' => [
                            [
                                'type' => 'read',
                                'command' => 'read',
                            ],
                        ],
                        '_dataArray' => [],
                        '_dataList' => [],
                    ],
                ],
                'meta' => [
                    'token' => '',
                ],
            ];
            $sessionRequestData = ['request' => json_encode($sessionRequestData)];
            $header = [
                'Authorization' => 'Bearer ' . $this->getAuthorization(),
                'Referer' => strtr('https://{domainHash}.plentymarkets-cloud-de.com/plenty/gwt/productive/2a28ee69/admin.html',
                    ['{domainHash}' => $this->getDomainHash()]),
            ];
            /** @var Client $client */
            $client = ObjectHelper::create($this->clientConfig, Client::class);
            $sessionUrl = strtr($this->sessionUrl, ['{domainHash}' => $this->getDomainHash()]);
            $this->sessionResponse = $client->post($sessionUrl, $sessionRequestData, $header)->send();
        }
        return $this->sessionResponse;
    }
}
