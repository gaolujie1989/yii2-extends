<?php
/**
 * @copyright Copyright (c) 2023
 */

namespace lujie\common\history\models;

use lujie\extend\helpers\ClassHelper;
use yii\db\ActiveQuery;

/**
 * Trait ModelHistoryRelationTrait
 *
 * @property string $historyClass
 *
 * @package lujie\common\history\models
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
trait ModelHistoryRelationTrait
{
    /**
     * @return ActiveQuery
     * @inheritdoc
     */
    public function getHistories(): ActiveQuery
    {
        $modelPk = self::primaryKey()[0];
        $historyClass = $this->historyClass ?? ModelHistory::class;
        $historyPk = $historyClass::primaryKey()[0];
        return $this->hasMany($historyClass, ['model_id' => $modelPk])
            ->andOnCondition(['model_type' => ClassHelper::getClassShortName(ClassHelper::getBaseRecordClass(static::class))])
            ->with(['details'])
            ->addOrderBy([$historyPk => SORT_DESC])
            ->limit(100);
    }
}
