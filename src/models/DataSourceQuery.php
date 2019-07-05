<?php

namespace lujie\data\recording\models;

use lujie\db\fieldQuery\behaviors\FieldQueryBehavior;

/**
 * This is the ActiveQuery class for [[DataRecord]].
 *
 * @method DataSourceQuery dataSourceId($dataSourceId)
 *
 * @method array|DataSource[] all($db = null)
 * @method array|DataSource|null one($db = null)
 *
 * @see DataSource
 */
class DataSourceQuery extends \yii\db\ActiveQuery
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
                    'data_source_id' => 'data_source_id',
                ]
            ]
        ];
    }
}
