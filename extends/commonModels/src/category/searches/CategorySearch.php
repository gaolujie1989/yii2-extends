<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\common\category\searches;

use lujie\common\category\models\Category;
use lujie\extend\db\SearchTrait;
use Yii;
use yii\db\ActiveQueryInterface;

/**
 * Class CategorySearch
 * @package lujie\common\category\searches
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class CategorySearch extends Category
{
    use SearchTrait;

    /**
     * @return ActiveQueryInterface
     * @inheritdoc
     */
    public function query(): ActiveQueryInterface
    {
        return $this->searchQuery()
            ->orderBy(['parent_id' => SORT_ASC, 'position' => SORT_ASC, 'category_id' => SORT_ASC]);
    }

    /**
     * @param array $row
     * @return array
     * @inheritdoc
     */
    public static function prepareArray(array $row): array
    {
        $row = static::prepareSearchArray($row);
        $row['label'] = $row['labels'][Yii::$app->language] ?? '';
        $row['label'] = $row['label'] ?: $row['name'];
        if (array_key_exists('leaf', $row)) {
            $row['isLeaf'] = empty($row['leaf']);
        }
        return $row;
    }
}