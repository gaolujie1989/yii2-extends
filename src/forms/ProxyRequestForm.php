<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\recording\forms;

use lujie\data\loader\DataLoaderInterface;
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
     * if url is set, method means HTTP_METHOD, else method is client function
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
            [['dataAccountId'], 'integer'],
            [['url', 'method'], 'string'],
            [['data'], 'safe'],
            [['method'], 'in', 'range' => ['GET', 'POST', 'PUT', 'DELETE']],
        ];
    }

    /**
     * @return bool
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function send(): bool
    {
        if (!$this->validate()) {
            return false;
        }

        /** @var RestOAuth2Client $client */
        $client = $this->dataClientLoader->get($this->dataAccountId);
        if ($client === null) {
            $this->addError('dataAccountId', 'Invalid dataAccountId, Null dataClient');
            return false;
        }
        $client = Instance::ensure($client, RestOAuth2Client::class);
        try {
            if ($this->url) {
                $this->responseData = $client->api($this->url, $this->method, $this->data);
            } else if ($this->method) {
                $this->responseData = $client->{$this->method}($this->data);
            } else {
                return false;
            }
        } catch (InvalidResponseException $e) {
            $this->addError('url', $e->getMessage());
        } catch (Exception $e) {
            $this->addError('url', $e->getMessage());
        }
        return true;
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function fields(): array
    {
        $fields = parent::fields();
        unset($fields['dataClientLoader']);
        return $fields;
    }
}
