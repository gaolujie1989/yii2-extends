<?php

namespace lujie\amazon\sp;

use DoubleBreak\Spapi\Credentials;
use DoubleBreak\Spapi\Signer;
use lujie\common\account\models\Account;
use lujie\common\oauth\models\AuthToken;
use lujie\data\loader\DataLoaderInterface;
use lujie\extend\psr\http\Yii2HttpHandler;
use Yii;
use yii\authclient\CacheStateStorage;
use yii\authclient\StateStorageInterface;
use yii\base\BaseObject;
use yii\di\Instance;
use yii\helpers\ArrayHelper;
use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/**
 * This class is autogenerated by the OpenAPI gii generator
 */
class BaseAmazonSPClientFactory extends BaseObject
{
    public $clientId;
    public $clientSecret;
    public $accessKey;
    public $accessSecret;
    public $roleARN;
    public $region = AmazonSPConst::REGION_EU_WEST_1;
    public $host = AmazonSPConst::HOST_EU_WEST_1;

    /**
     * @var DataLoaderInterface
     */
    public $configLoader;

    /**
     * @var CacheStateStorage
     */
    public $stateStorage = [
        'class' => CacheStateStorage::class,
    ];

    /**
     * @var Yii2HttpHandler
     */
    public $httpHandler = [
//        'requestOptions' => []
    ];

    /**
     * @var Signer
     */
    protected $signer;

    /**
     * @var array
     */
    private $_clients = [];

    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        $this->stateStorage = Instance::ensure($this->stateStorage, StateStorageInterface::class);
        $this->httpHandler = Instance::ensure($this->httpHandler, Yii2HttpHandler::class);
        $this->signer = new Signer();
        if ($this->configLoader) {
            $this->configLoader = Instance::ensure($this->configLoader, DataLoaderInterface::class);
        }
    }

    /*
     * @param Account $account
     */
    protected function getConfig(Account $account): array
    {
        $config = [
            //Guzzle configuration
            'http' => ['handler' => [$this->httpHandler, 'sendAsync']],

            //LWA: Keys needed to obtain access token from Login With Amazon Service
            'refresh_token' => null,
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,

            //STS: Keys of the IAM role which are needed to generate Secure Session
            // (a.k.a Secure token) for accessing and assuming the IAM role
            'access_key' => $this->accessKey,
            'secret_key' => $this->accessSecret,
            'role_arn' => $this->roleARN,

            //API: Actual configuration related to the SP API :)
            'region' => $this->region,
            'host' => $this->host
        ];
        if ($this->configLoader) {
            $config = array_merge($config, $this->configLoader->get($account) ?: []);
        }
        $additional = $account->additional ?: [];
        $accountConfig = array_filter(ArrayHelper::filter($additional, array_keys($config)));
        return array_merge($config, $accountConfig);
    }

    /**
     * @param string $clientClass
     * @param Account $account
     * @param string|null $authService
     * @return BaseAmazonSPClient|null
     * @throws \yii\base\InvalidConfigException
     * @throws \Exception
     * @inheritdoc
     */
    protected function createClient(string $clientClass, Account $account, ?string $authService = null): ?BaseAmazonSPClient
    {
        $accountId = $account->account_id;
        $key = $clientClass . '-' . $account::class . '-' . $accountId;
        if (empty($this->_clients[$key])) {
            $authService = $authService ?: $account->type;
            $authToken = AuthToken::find()->userId($accountId)->authService($authService)->one();
            if ($authToken === null) {
                Yii::error("Account {$account->name} is not authed", __METHOD__);
                return null;
            }
            if ($authToken->refresh_token_expires_at && $authToken->refresh_token_expires_at - time() < 86400 * 10) {
                $day = round(($authToken->refresh_token_expires_at - time()) / 86400);
                Yii::error("Refresh token of account {$account->name} is expires in {$day} days.", __METHOD__);
                return null;
            }
            $config = $this->getConfig($account);
            $config['refresh_token'] = $authToken->refresh_token;

            $id = Inflector::camel2id(StringHelper::basename(get_class($this))) . '-' . $accountId;
            $tokenStorage = new StateTokenStorage($this->stateStorage, ['keyPrefix' => $id]);
            $credentials = new Credentials($tokenStorage, $this->signer, $config);

            /** @var BaseAmazonSPClient $client */
            $client = new $clientClass($credentials, $config);
            $this->_clients[$key] = $client;
        }
        return $this->_clients[$key];
    }
}
