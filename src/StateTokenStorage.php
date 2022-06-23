<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\amazon\sp;

use DoubleBreak\Spapi\TokenStorageInterface;
use yii\authclient\StateStorageInterface;
use yii\base\BaseObject;
use yii\di\Instance;

/**
 * Class StateTokenStorage
 * @package lujie\amazon\sp
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class StateTokenStorage extends BaseObject implements TokenStorageInterface
{
    /**
     * @var StateStorageInterface
     */
    private $stateStorage;

    /**
     * @var string
     */
    public $keyPrefix = 'AmazonToken:';

    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function __construct($stateStorage, array $config = [])
    {
        $this->stateStorage = Instance::ensure($stateStorage, StateStorageInterface::class);
        parent::__construct($config);
    }

    /**
     * @param $key
     * @return array|null
     * @inheritdoc
     */
    public function getToken($key): ?array
    {
        return $this->stateStorage->get($this->keyPrefix . $key) ?: null;
    }

    /**
     * @param $key
     * @param $value
     * @inheritdoc
     */
    public function storeToken($key, $value): void
    {
        $this->stateStorage->set($this->keyPrefix . $key, $value);
    }
}