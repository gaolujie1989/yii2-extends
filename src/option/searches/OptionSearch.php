<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\common\option\searches;

use lujie\common\option\models\Option;
use lujie\common\option\models\OptionQuery;
use lujie\extend\db\SearchTrait;
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
    public $type;

    /**
     * @return array
     * @inheritdoc
     */
    public function rules(): array
    {
        return array_merge($this->searchRules(), [
            [['type'], 'safe'],
        ]);
    }

    /**
     * @return string[]
     * @inheritdoc
     */
    public function searchKeyAttributes(): array
    {
        return ['name', 'labels'];
    }

    /**
     * @return ActiveQueryInterface
     * @inheritdoc
     */
    public function query(): ActiveQueryInterface
    {
        /** @var OptionQuery $query */
        $query = $this->searchQuery(null, 'o');
        if ($this->type) {
            $query->innerJoinWith(['parent p']);
            QueryHelper::filterValue($query, ['p.value' => $this->type]);
        } else {
            $query->parentId(0);
        }
        $query->orderBy(['o.position' => SORT_ASC]);
        return $query;
    }

    /**
     * @param array $row
     * @return array
     * @inheritdoc
     */
    public static function prepareArray(array $row): array
    {
        $row = ModelHelper::prepareArray($row, static::class);
        if (isset($row['parent'])) {
            $row['type'] = $row['parent']['value'];
            unset($row['parent']);
        } else {
            $row['type'] = 'root';
        }
        if ($row['value_type'] === Option::VALUE_TYPE_INT) {
            $row['value'] = (int)$row['value'];
        } else if ($row['value_type'] === Option::VALUE_TYPE_FLOAT) {
            $row['value'] = (float)$row['value'];
        }
        return $row;
    }
}
