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
     * @return array
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public static function typecast(string $modelClass, array $data): array
    {
        if (is_subclass_of($modelClass, ActiveRecord::class)) {
            $columns = $modelClass::getTableSchema()->columns;
            return static::phpTypecastInternal($data, $columns);
        }
        return $data;
    }

    /**
     * @param array $data
     * @param ColumnSchema[] $columns
     * @return array
     * @inheritdoc
     */
    protected static function phpTypecastInternal(array $data, array $columns): array
    {
        $closure = static function ($row) use ($columns) {
            foreach ($row as $name => $value) {
                if (isset($columns[$name]) && is_string($value)) { //only string need phpTypecast, avoid phpTypecast twice cause error
                    $row[$name] = $columns[$name]->phpTypecast($value);
                }
            }
            return $row;
        };
        if (ArrayHelper::isAssociative($data, false)) {
            return $closure($data);
        }
        return array_map($closure, $data);
    }

    /**
     * @param array $data
     * @param string $table
     * @param Connection|null $db
     * @return array
     * @throws \yii\base\NotSupportedException
     * @inheritdoc
     */
    public static function phpTypecast(array $data, string $table, Connection $db = null): array
    {
        $db = $db ?: Yii::$app->getDb();
        $columns = $db->getSchema()->getTableSchema($table)->columns;
        return static::phpTypecastInternal($data, $columns);
    }
}
