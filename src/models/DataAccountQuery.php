<?php

namespace lujie\data\recording\models;

use lujie\db\fieldQuery\behaviors\FieldQueryBehavior;
use lujie\extend\constants\StatusConst;

/**
 * This is the ActiveQuery class for [[DataRecord]].
 *
 * @method DataAccountQuery id($id)
 * @method DataAccountQuery name($name)
 * @method DataAccountQuery type($type)
 * @method DataAccountQuery active()
 * @method DataAccountQuery inactive()
 *
 * @method int getAccountId()
 *
 * @method array|DataAccount[] all($db = null)
 * @method array|DataAccount|null one($db = null)
 *
 * @see DataAccount
 */
class DataAccountQuery extends \yii\db\ActiveQuery
{
    /**
     * @return array
     * @inheritdoc
     */
    public function behaviors(): array
    {
        return [
            'fieldQuery' => [
                'class' => FieldQueryBehavior::class,
                'queryFields' => [
                    'name' => 'name',
                    'type' => 'type',
                ],
                'queryConditions' => [
                    'active' => ['status' => StatusConst::STATUS_ACTIVE],
                    'inactive' => ['status' => StatusConst::STATUS_INACTIVE],
                ],
                'queryReturns' => [
                    'getAccountId' => ['data_account_id', FieldQueryBehavior::RETURN_SCALAR],
                ]
            ]
        ];
    }
}
