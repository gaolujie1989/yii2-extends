<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\fulfillment\models;

use yii\db\ActiveQuery;

/**
 * Trait FulfillmentAccountRelationTrait
 *
 * @property FulfillmentAccount $account
 *
 * @package lujie\fulfillment\models
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
trait FulfillmentAccountRelationTrait
{
    /**
     * @return array
     * @inheritdoc
     */
    public function extraFields(): array
    {
        return array_merge(parent::extraFields(), [
            'account' => 'account'
        ]);
    }

    /**
     * @return ActiveQuery|FulfillmentAccountQuery
     * @inheritdoc
     */
    public function getAccount(): ActiveQuery
    {
        return $this->hasOne(FulfillmentAccount::class, ['account_id' => 'fulfillment_account_id']);
    }
}
