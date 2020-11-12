<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\fulfillment\models;

use lujie\common\account\models\Account;
use lujie\common\account\models\AccountQuery;
use yii\db\ActiveQueryInterface;
use yii\db\BaseActiveRecord;

/**
 * Trait FulfillmentAccountRelationTrait
 *
 * @property Account $fulfillmentAccount
 *
 * @package lujie\fulfillment\models
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
trait FulfillmentAccountRelationTrait
{
    /**
     * @return ActiveQueryInterface|AccountQuery
     * @inheritdoc
     */
    public function getFulfillmentAccount(): ActiveQueryInterface
    {
        /** @var BaseActiveRecord $this */
        return $this->hasOne(FulfillmentAccount::class, ['fulfillment_account_id' => 'fulfillment_account_id']);
    }
}
