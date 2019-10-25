<?php

namespace lujie\fulfillment\models;

use lujie\db\fieldQuery\behaviors\FieldQueryBehavior;
use lujie\extend\constants\StatusConst;

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
                    'active' => ['status' => StatusConst::STATUS_ACTIVE],
                    'inActive' => ['status' => StatusConst::STATUS_INACTIVE],
                ]
            ]
        ]);
    }
}
