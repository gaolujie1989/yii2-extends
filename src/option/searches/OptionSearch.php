<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\common\option\searches;

use lujie\common\option\models\Option;
use yii\db\ActiveQuery;

/**
 * Class OptionSearch
 * @package lujie\common\option\searches
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class OptionSearch extends Option
{
    /**
     * @return array
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            [['parent_id', 'key', 'name'], 'safe'],
        ];
    }

    /**
     * @return ActiveQuery
     * @inheritdoc
     */
    public function query(): ActiveQuery
    {
        return static::find()
            ->andFilterWhere(['parent_id' => $this->parent_id])
            ->andFilterWhere(['LIKE', 'key', $this->key])
            ->andFilterWhere(['LIKE', 'name', $this->name]);
    }

    /**
     * @param array $row
     * @return array
     * @inheritdoc
     */
    public function prepareArray(array $row): array
    {
        return $row;
    }
}