<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\common\option\providers;

use lujie\common\option\models\Option;
use lujie\common\option\searches\OptionSearch;
use lujie\extend\helpers\QueryHelper;
use yii\base\BaseObject;
use yii\helpers\ArrayHelper;

/**
 * Class OptionProvider
 * @package lujie\common\option
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class OptionProvider extends BaseObject implements OptionProviderInterface
{
    public $like = true;

    /**
     * @param string $type
     * @return bool
     * @inheritdoc
     */
    public function hasType(string $type): bool
    {
        return Option::find()->type($type)->exists();
    }

    /**
     * @param string $type
     * @param string $key
     * @param bool|string $like
     * @return array
     * @inheritdoc
     * @throws \Exception
     */
    public function getOptions(string $type, ?string $key = null): array
    {
        $query = Option::find()
            ->type($type)
            ->select(['option_id', 'value', 'value_type', 'name', 'tag', 'labels'])
            ->orderBy(['position' => SORT_ASC])
            ->asArray();
        if ($key) {
            QueryHelper::filterKey($query, ['value', 'name', 'labels'], $key, $this->like);
        }
        $rows = $query->all();
        return array_map(static function($row) {
            $row = OptionSearch::formatValueLabel($row);
            $row['id'] = $row['option_id'];
            unset($row['option_id'], $row['value_type'], $row['labels']);
            return $row;
        }, $rows);
    }

    /**
     * @param string $type
     * @param string $value
     * @param array $data
     * @return bool
     * @inheritdoc
     */
    public function addOption(string $type, string $value, array $data = []): bool
    {
        $query = Option::find()->type($type)->value($value);
        if ($query->exists()) {
            return true;
        }
        $option = new Option();
        $option->type = $type;
        $option->value = $value;
        $option->name = $value;
        $option->setAttributes($data);
        return $option->save(false);
    }
}