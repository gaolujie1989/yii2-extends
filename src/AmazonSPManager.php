<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\amazon\sp;

use lujie\common\account\models\Account;
use lujie\extend\caching\CachingTrait;
use yii\base\BaseObject;

/**
 * Class AmazonSPManager
 * @package lujie\amazon\sp
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class AmazonSPManager extends BaseObject
{
    use CachingTrait;

    public $clientId;
    public $clientSecret;

    public $accessKey;
    public $accessSecret;
    public $roleARN;

    public $refreshToken;

    public $debug = false;

    /**
     * @var AmazonSPClient[]
     */
    private $_amazonSPClients;

    /**
     * @param Account $account
     * @return AmazonSPClient
     * @inheritdoc
     */
    public function getAmazonSPClient(Account $account): AmazonSPClient
    {
        $accountKey = '[' . $account->account_id . ']' . $account->username;
        if (empty($this->_amazonSPClients[$accountKey])) {
            $config = [
                'clientId' => $this->clientId,
                'clientSecret' => $this->clientSecret,
                'accessKey' => $this->accessKey,
                'accessSecret' => $this->accessSecret,
                'roleARN' => $this->roleARN,
                'refreshToken' => $this->refreshToken,
            ];
            $additional = $account->additional ?: [];
            foreach ($config as $key => $value) {
                if (isset($additional[$key])) {
                    $config[$key] = $additional[$key];
                }
            }
            $authToken = $account->authToken;
            if ($authToken) {
                $config['refreshToken'] = $authToken->refresh_token;
            }
            if ($this->debug) {
                $config['http']['debug'] = true;
            }
            $this->_amazonSPClients[$accountKey] = new AmazonSPClient($config);
        }
        return $this->_amazonSPClients[$accountKey];
    }
}