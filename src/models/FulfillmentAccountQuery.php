<?php

namespace lujie\fulfillment\models;

use lujie\db\fieldQuery\behaviors\FieldQueryBehavior;

/**
 * This is the ActiveQuery class for [[FulfillmentAccount]].
 *
 * @method FulfillmentAccountQuery id($id)
 *
 * @method FulfillmentAccountQuery active()
 * @method FulfillmentAccountQuery inActive()
 *
 * @method array|FulfillmentAccount[] all($db = null)
 * @method array|FulfillmentAccount|null one($db = null)
 *
 * @see FulfillmentAccount
 */
class FulfillmentAccountQuery extends \yii\db\ActiveQuery
{
    /**
     * @return array
     * @inheritdoc
     */
    public function behaviors(): array
    {
        return array_merge(parent::behaviors(), [
            'fieldQuery' => [
                'class' => FieldQueryBehavior::class,
                'queryConditions' => [
                    'active' => ['status' => FulfillmentAccount::STATUS_ACTIVE],
                    'inActive' => ['status' => FulfillmentAccount::STATUS_INACTIVE],
                ]
            ]
        ]);
    }
}
