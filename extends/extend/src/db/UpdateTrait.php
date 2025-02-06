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
     * @param $attributes
     * @param $condition
     * @param $params
     * @return int
     * @inheritdoc
     */
    public static function updateAll($attributes, $condition = '', $params = []): int
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
        $idChunks = array_chunk($ids, 200);
        foreach ($idChunks as $chunkIds) {
            $rows[] = parent::updateAll($attributes, [$pk => $chunkIds], $params);
        }
        return array_sum($rows);
    }
}
