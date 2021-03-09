<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\common\option\searches;

use lujie\common\option\models\Option;
use lujie\common\option\models\OptionQuery;
use lujie\extend\base\SearchTrait;
use lujie\extend\helpers\ModelHelper;
use lujie\extend\helpers\QueryHelper;
use yii\db\ActiveQueryInterface;

/**
 * Class OptionSearch
 * @package lujie\common\option\searches
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class OptionSearch extends Option
{
    use SearchTrait;

    /**
     * @var string
     */
    public $parent_key;

    /**
     * @return array
     * @inheritdoc
     */
    public function rules(): array
    {
        return array_merge(ModelHelper::searchRules($this), [
            [['parent_key'], 'safe'],
        ]);
    }

    /**
     * @return ActiveQueryInterface
     * @inheritdoc
     */
    public function query(): ActiveQueryInterface
    {
        /** @var OptionQuery $query */
        $query = ModelHelper::query($this);
        if ($this->parent_key) {
            $query = $query->innerJoinWith(['parentOption po']);
            QueryHelper::filterValue($query, ['po.key' => $this->parent_key]);
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
        $alias = [
            'parent_key' => 'parentOption.key'
        ];
        $relations = ['parentOption'];
        return ModelHelper::prepareArray($row, static::class, $alias, $relations);
    }
}
