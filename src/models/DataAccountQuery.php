<?php

namespace lujie\data\recording\models;

use lujie\db\fieldQuery\behaviors\FieldQueryBehavior;
use lujie\extend\constants\StatusConst;

/**
 * This is the ActiveQuery class for [[DataRecord]].
 *
 * @method DataAccountQuery id($id)
 * @method DataAccountQuery orderById($sort = SORT_ASC)
 * @method int getId()
 * @method array getIds()
 *
 * @method DataAccountQuery dataAccountId($dataAccountId)
 * @method DataAccountQuery type($type)
 * @method DataAccountQuery status($status)
 * @method DataAccountQuery name($name)
 *
 * @method DataAccountQuery active()
 * @method DataAccountQuery inactive()
 * @method int getAccountId()
 *
 * @method array|DataAccount[] all($db = null)
 * @method array|DataAccount|null one($db = null)
 * @method array|DataAccount[] each($batchSize = 100, $db = null)
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
                    'dataAccountId' => 'data_account_id',
                    'type' => 'type',
                    'status' => 'status',
                    'name' => 'name',
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
