<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\recording\searches;

use lujie\data\recording\models\DataAccount;
use yii\db\ActiveQuery;

/**
 * Class DataAccountSearch
 * @package lujie\data\recording\searches
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class DataAccountSearch extends DataAccount
{
    /**
     * @return array
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            [['name', 'type', 'username', 'status'], 'safe'],
        ];
    }

    /**
     * @return ActiveQuery
     * @inheritdoc
     */
    public function query(): ActiveQuery
    {
        $query = static::find()->andFilterWhere([
            'type' => $this->type,
            'status' => $this->status
        ]);
        $query->andFilterWhere(['LIKE', 'name', $this->name])
            ->andFilterWhere(['LIKE', 'username', $this->username]);
        return $query;
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function extraFields(): array
    {
        return array_merge(parent::extraFields(), [
            'dataSource',
        ]);
    }
}
