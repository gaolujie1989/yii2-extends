<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\sales\channel\models;

use yii\db\ActiveQuery;

/**
 * Trait SalesChannelAccountTrait
 *
 * @property SalesChannelAccount $account
 *
 * @package lujie\sales\channel\models
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
trait SalesChannelAccountRelationTrait
{
    public function getAccount(): ActiveQuery
    {
        return $this->hasOne(SalesChannelAccount::class, ['account_id' => 'sales_channel_account_id'])
            ->where([])
            ->andOnCondition(['model_type' => SalesChannelAccount::MODEL_TYPE]);
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function extraFields(): array
    {
        return array_merge(parent::fields(), [
            'account' => 'account'
        ]);
    }
}
