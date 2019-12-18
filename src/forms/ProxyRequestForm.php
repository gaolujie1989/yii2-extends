<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\recording\forms;

use lujie\data\loader\DataLoaderInterface;
use lujie\data\recording\models\DataAccount;
use lujie\extend\authclient\RestOAuth2Client;
use yii\authclient\InvalidResponseException;
use yii\base\Exception;
use yii\base\Model;
use yii\di\Instance;

/**
 * Class DataProxyRequestForm
 * @package lujie\data\recording\forms
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ProxyRequestForm extends Model
{
    /**
     * @var int
     */
    public $dataAccountId;

    /**
     * @var string
     */
    public $url;

    /**
     * @var string
     */
    public $method;

    /**
     * @var array
     */
    public $data;

    /**
     * @var array
     */
    public $responseData;

    /**
     * @var DataLoaderInterface
     */
    public $dataClientLoader = 'dataClientLoader';

    /**
     * @var DataAccount
     */
    private $_dataAccount;

    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        $this->dataClientLoader = Instance::ensure($this->dataClientLoader, DataLoaderInterface::class);
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            [['dataAccountId', 'url', 'method'], 'required'],
            [['dataAccountId'], 'validateAccountId'],
            [['url'], 'string'],
            [['method'], 'in', 'range' => ['GET', 'POST', 'PUT', 'DELETE']],
            [['data'], 'safe'],
        ];
    }

    public function validateAccountId(): void
    {
        if ($this->getDataAccount() === null) {
            $this->addError('dataAccountId', 'Invalid dataAccountId');
        }
    }

    /**
     * @return DataAccount|null
     * @inheritdoc
     */
    public function getDataAccount(): ?DataAccount
    {
        if ($this->_dataAccount === null) {
            $this->_dataAccount = DataAccount::findOne($this->dataAccountId);
        }
        return $this->_dataAccount;
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function send(): bool
    {
        if (!$this->validate()) {
            return false;
        }

        /** @var RestOAuth2Client $client */
        $client = $this->dataClientLoader->get($this->dataAccountId);
        try {
            $this->responseData = $client->api($this->url, $this->method, $this->data);
        } catch (InvalidResponseException $e) {
            $this->addError('url', $e->getMessage());
        } catch (Exception $e) {
            $this->addError('url', $e->getMessage());
        }
        return true;
    }
}
