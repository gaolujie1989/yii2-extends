<?php

namespace lujie\data\recording\models;

use lujie\db\fieldQuery\behaviors\FieldQueryBehavior;

/**
 * This is the ActiveQuery class for [[DataRecord]].
 *
 * @method DataAccountQuery name($name)
 *
 * @method int getAccountId()
 *
 * @method array|DataAccount[] all($db = null)
 * @method array|DataAccount|null one($db = null)
 *
 * @see DataRecord
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
                ],
                'queryReturns' => [
                    'getAccountId' => ['data_account_id', FieldQueryBehavior::RETURN_SCALAR],
                ]
            ]
        ];
    }
}
