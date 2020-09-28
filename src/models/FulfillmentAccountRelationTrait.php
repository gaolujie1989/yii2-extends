<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\fulfillment\models;

use yii\db\ActiveQueryInterface;
use yii\db\BaseActiveRecord;

/**
 * Trait FulfillmentAccountRelationTrait
 * @package lujie\fulfillment\models
 */
trait FulfillmentAccountRelationTrait
{
    /**
     * @return ActiveQueryInterface|FulfillmentAccountQuery
     * @inheritdoc
     */
    public function getFulfillmentAccount(): ActiveQueryInterface
    {
        /** @var BaseActiveRecord $this */
        return $this->hasOne(FulfillmentAccount::class, ['fulfillment_account_id' => 'fulfillment_account_id']);
    }
}
