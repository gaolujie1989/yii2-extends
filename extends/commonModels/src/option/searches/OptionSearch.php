<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\common\option\searches;

use lujie\common\option\models\Option;
use lujie\extend\db\SearchTrait;
use Yii;
use yii\db\ActiveQueryInterface;
use yii\helpers\Json;

/**
 * Class OptionSearch
 * @package lujie\common\option\searches
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class OptionSearch extends Option
{
    use SearchTrait;

    /**
     * @return ActiveQueryInterface
     * @inheritdoc
     */
    public function query(): ActiveQueryInterface
    {
        return $this->searchQuery()->orderBy(['type' => SORT_ASC, 'position' => SORT_ASC]);
    }

    /**
     * @param array $row
     * @return array
     * @inheritdoc
     */
    public static function prepareArray(array $row): array
    {
        $row = static::prepareSearchArray($row);
        return static::formatValueLabel($row);
    }

    /**
     * @param array $row
     * @return array
     * @inheritdoc
     */
    public static function formatValueLabel(array $row): array
    {
        if ((int)$row['value_type'] === Option::VALUE_TYPE_INT) {
            $row['value'] = (int)$row['value'];
        } else if ((int)$row['value_type'] === Option::VALUE_TYPE_FLOAT) {
            $row['value'] = (float)$row['value'];
        }
        $row['labels'] = is_array($row['labels']) ? $row['labels'] : Json::decode($row['labels']);
        $row['label'] = $row['labels'][Yii::$app->language] ?? '';
        $row['label'] = $row['label'] ?: $row['name'];
        return $row;
    }
}
