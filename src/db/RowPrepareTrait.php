<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\db;

use lujie\extend\helpers\ModelHelper;

/**
 * Trait RowPrepareTrait
 * @package lujie\extend\db
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
trait RowPrepareTrait
{
    /**
     * @param array $row
     * @return array
     * @throws \Exception
     * @inheritdoc
     */
    public static function prepareArray(array $row): array
    {
        return self::prepareSearchArray($row);
    }

    /**
     * @param array $row
     * @return array
     * @throws \Exception
     * @inheritdoc
     */
    public static function prepareRows(array $rows): array
    {
        return array_map([static::class, 'prepareArray'], $rows);
    }

    /**
     * @param $row
     * @param array $aliasProperties
     * @param array $relations
     * @return array
     * @throws \Exception
     * @inheritdoc
     */
    protected static function prepareSearchArray($row, array $aliasProperties = [], array $relations = []): array
    {
        return ModelHelper::prepareArray($row, static::class, $aliasProperties, $relations);
    }
}