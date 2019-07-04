<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\recording\searches;

use lujie\data\recording\models\DataSource;
use yii\db\ActiveQuery;

/**
 * Class DataSourceSearch
 * @package lujie\data\recording\searches
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class DataSourceSearch extends DataSource
{
    /**
     * @return array
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            [['data_account_id', 'type', 'status'], 'safe'],
        ];
    }

    /**
     * @return ActiveQuery
     * @inheritdoc
     */
    public function query(): ActiveQuery
    {
        return static::find()->andFilterWhere([
            'data_account_id' => $this->data_account_id,
            'type' => $this->type,
            'status' => $this->status
        ]);
    }
}
