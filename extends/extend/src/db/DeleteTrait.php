<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\db;

use lujie\extend\helpers\ClassHelper;
use yii\db\ActiveQuery;
use yii\db\BaseActiveRecord;
use yii\db\Exception;

/**
 * Trait SaveTrait
 * @package lujie\extend\db
 */
trait DeleteTrait
{
    /**
     * @param null $condition
     * @param array $params
     * @return int
     * @inheritdoc
     */
    public static function deleteAll($condition = null, $params = []): int
    {
        $primaryKey = static::primaryKey();
        if (count($primaryKey) > 1) {
            return parent::deleteAll($condition, $params);
        }
        $pk = $primaryKey[0];
        /** @var ActiveQuery $query */
        $query = static::find();
        $ids = $query->andWhere($condition, $params)->column();
        if (empty($ids)) {
            return 0;
        }
        return parent::deleteAll([$pk => $ids]);
    }
}
