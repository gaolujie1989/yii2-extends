<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\fulfillment;

use lujie\data\loader\BaseDataLoader;
use lujie\fulfillment\models\FulfillmentAccount;
use Yii;
use yii\base\InvalidConfigException;

/**
 * Class PmFulfillmentService
 * @package lujie\fulfillment\pm
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
abstract class BaseFulfillmentServiceLoader extends BaseDataLoader
{
    /**
     * @var string
     */
    public $fulfillmentServiceAccountType = '';

    /**
     * @var string
     */
    public $fulfillmentServiceClass = '';

    /**
     * @var array
     */
    public $fulfillmentServiceConfig = [];

    /**
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        if (empty($this->fulfillmentServiceAccountType) || empty($this->fulfillmentServiceClass)) {
            throw new InvalidConfigException('Fulfillment service account type or class must be set');
        }
    }

    /**
     * @param mixed $key
     * @return FulfillmentServiceInterface|null
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function get($key): ?FulfillmentServiceInterface
    {
        /** @var ?FulfillmentAccount $account */
        $account = FulfillmentAccount::findOne($key);
        if ($account === null || $account->type !== $this->fulfillmentServiceAccountType) {
            return null;
        }
        /** @var FulfillmentServiceInterface $fulfillmentService */
        $fulfillmentService = Yii::createObject(array_merge(
            [
                'class' => $this->fulfillmentServiceClass,
                'account' => $account,
            ],
            $this->getConfig($account),
            $this->fulfillmentServiceConfig,
        ));
        if ($account->options) {
            foreach ($account->options as $property => $value) {
                if ($fulfillmentService->hasProperty($property)) {
                    $fulfillmentService->{$property} = $value;
                }
            }
        }
        return $fulfillmentService;
    }

    /**
     * @param FulfillmentAccount $account
     * @return array
     * @inheritdoc
     */
    abstract protected function getConfig(FulfillmentAccount $account): array;
}
