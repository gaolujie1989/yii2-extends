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
    public $parent_key;

    /**
     * @return array
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            [['parent_id', 'key', 'name'], 'safe'],
            [['parentKey'], 'safe'],
        ];
    }

    /**
     * @return ActiveQuery
     * @inheritdoc
     */
    public function query(): ActiveQuery
    {
        $query = static::find()
            ->innerJoinWith(['parentOption'])
            ->andFilterWhere(['parent_id' => $this->parent_id])
            ->andFilterWhere(['LIKE', 'key', $this->key])
            ->andFilterWhere(['LIKE', 'name', $this->name]);
        if ($this->parent_key) {
            $parentIds = static::find()->key($this->parent_key)->getIds();
            $query->joinWith(['parent_id' => $parentIds]);
        }
        return $query;
    }

    /**
     * @param array $row
     * @return array
     * @inheritdoc
     */
    public static function prepareArray(array $row): array
    {
        $row['id'] = $row['option_id'];
        if (isset($row['parentOption'])) {
            $row['parent_key'] = $row['parentOption']['key'];
            unset($row['parentOption']);
        }
        return $row;
    }
}