<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\common\option\providers;

use lujie\common\option\models\Option;
use lujie\common\option\searches\OptionSearch;
use lujie\extend\helpers\QueryHelper;
use yii\base\BaseObject;
use yii\db\ActiveQueryInterface;
use yii\db\QueryInterface;
use yii\di\Instance;
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
     * @param string|null $key
     * @param array|null $values
     * @param array|null $params
     * @return array
     * @inheritdoc
     */
    public function getOptions(string $type, ?string $key = null, ?array $values = null, ?array $params = null): array
    {
        $query = $this->getQuery($type, $key, $values, $params);
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
     * @param string|null $key
     * @param array|null $values
     * @param array|null $params
     * @return QueryInterface
     * @inheritdoc
     */
    protected function getQuery(string $type, ?string $key = null, ?array $values = null, ?array $params = null): QueryInterface
    {
        $query = Option::find()
            ->type($type)
            ->select(['option_id', 'value', 'value_type', 'name', 'tag', 'labels'])
            ->orderBy(['position' => SORT_ASC])
            ->asArray();
        if ($key) {
            QueryHelper::filterKey($query, ['value', 'name', 'labels'], $key, $this->like);
        }
        if ($values) {
            QueryHelper::filterValue($query, ['value' => $values]);
        }
        return $query;
    }
}
