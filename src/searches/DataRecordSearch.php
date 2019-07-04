<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\recording\searches;

use lujie\data\recording\models\DataRecord;
use yii\db\ActiveQuery;

/**
 * Class DataRecordSearch
 * @package lujie\data\recording\searches
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class DataRecordSearch extends DataRecord
{
    /**
     * @return array
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            [['data_account_id', 'data_type', 'data_id', 'data_key', 'data_parent_id'], 'safe'],
        ];
    }

    /**
     * @return ActiveQuery
     * @inheritdoc
     */
    public function query(): ActiveQuery
    {
        return static::find()->andFilterWhere(
            $this->getAttributes(['data_account_id', 'data_type', 'data_id', 'data_key', 'data_parent_id'])
        );
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function extraFields(): array
    {
        return array_merge(parent::extraFields(), [
            'recordData'
        ]);
    }
}
