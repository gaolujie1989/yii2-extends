<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\sales\channel;

use lujie\data\loader\BaseDataLoader;
use lujie\sales\channel\models\SalesChannelAccount;
use Yii;

/**
 * Class PmSalesChannelLoader
 * @package lujie\sales\channel\pm
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
abstract class BaseSalesChannelLoader extends BaseDataLoader
{
    /**
     * @var string
     */
    public $salesChannelAccountType = '';

    /**
     * @var string
     */
    public $salesChannelClass = '';

    /**
     * @var array
     */
    public $salesChannelConfig = [];

    /**
     * @param mixed $key
     * @return SalesChannelInterface|null
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function get($key): ?SalesChannelInterface
    {
        /** @var ?SalesChannelAccount $account */
        $account = SalesChannelAccount::findOne($key);
        if ($account === null || $account->type !== $this->salesChannelAccountType) {
            return null;
        }
        /** @var SalesChannelInterface $salesChannel */
        $salesChannel = Yii::createObject(array_merge(
            [
                'class' => $this->salesChannelClass,
                'account' => $account,
            ],
            $this->getConfig($account),
            $this->salesChannelConfig,
        ));
        if ($account->options) {
            foreach ($account->options as $property => $value) {
                if ($salesChannel->hasProperty($property)) {
                    $salesChannel->{$property} = $value;
                }
            }
        }
        return $salesChannel;
    }

    /**
     * @param SalesChannelAccount $account
     * @return array
     * @inheritdoc
     */
    abstract protected function getConfig(SalesChannelAccount $account): array;
}
