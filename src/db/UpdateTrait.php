<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\db;

use yii\db\ActiveQuery;

/**
 * Trait UpdateTrait
 * @package lujie\extend\db
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
trait UpdateTrait
{
    /**
     * @param array $attributes
     * @param array|string $condition
     * @param array $params
     * @return int
     * @inheritdoc
     */
    public static function updateAll($attributes, $condition = '', $params = []): int
    {
        return static::updateAllByPk($attributes, $condition, $params);
    }

    /**
     * @param array $attributes
     * @param array|string $condition
     * @param array $params
     * @param int $batchSize
     * @return int
     * @inheritdoc
     */
    public static function updateAllByPk(array $attributes, array|string $condition = '', array $params = [], int $batchSize = 200): int
    {
        $primaryKey = static::primaryKey();
        if (count($primaryKey) > 1) {
            return parent::updateAll($attributes, $condition, $params);
        }
        $pk = $primaryKey[0];
        /** @var ActiveQuery $query */
        $query = static::find();
        $ids = $query->andWhere($condition, $params)->column();
        if (empty($ids)) {
            return 0;
        }
        $rows = [];
        $idChunks = array_chunk($ids, $batchSize);
        foreach ($idChunks as $chunkIds) {
            $rows[] = parent::updateAll($attributes, [$pk => $chunkIds], $params);
        }
        return array_sum($rows);
    }
}
