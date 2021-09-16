<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\helpers;

use Yii;
use yii\db\ActiveRecord;
use yii\db\ColumnSchema;
use yii\db\Connection;
use yii\helpers\ArrayHelper;

/**
 * Class ActiveDataHelper
 * @package lujie\extend\helpers
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ActiveDataHelper
{
    /**
     * @param string $modelClass
     * @param array $data
     * @param bool|null $multi
     * @return array
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public static function typecast(array $data, string $modelClass, ?bool $multi = null): array
    {
        if (is_subclass_of($modelClass, ActiveRecord::class)) {
            $columns = $modelClass::getTableSchema()->columns;
            return static::phpTypecastInternal($data, $columns, $multi);
        }
        return $data;
    }

    /**
     * @param array $data
     * @param ColumnSchema[] $columns
     * @param bool|null $multi
     * @return array
     * @inheritdoc
     */
    protected static function phpTypecastInternal(array $data, array $columns, ?bool $multi = null): array
    {
        $closure = static function ($row) use ($columns) {
            foreach ($row as $name => $value) {
                if (isset($columns[$name]) && is_string($value)) { //only string need phpTypecast, avoid phpTypecast twice cause error
                    $row[$name] = $columns[$name]->phpTypecast($value);
                }
            }
            return $row;
        };
        if ($multi === null) {
            $multi = !ArrayHelper::isAssociative($data, false);
        }
        return $multi ? array_map($closure, $data) : $closure($data);
    }

    /**
     * @param array $data
     * @param string $table
     * @param Connection|null $db
     * @param bool|null $multi
     * @return array
     * @throws \yii\base\NotSupportedException
     * @inheritdoc
     */
    public static function phpTypecast(array $data, string $table, Connection $db = null, ?bool $multi = null): array
    {
        $db = $db ?: Yii::$app->getDb();
        $columns = $db->getSchema()->getTableSchema($table)->columns;
        return static::phpTypecastInternal($data, $columns, $multi);
    }
}
