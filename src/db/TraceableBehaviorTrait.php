<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\db;

use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\BaseActiveRecord;

/**
 * Trait TraceableBehaviorTrait
 * @package lujie\extend\db
 */
trait TraceableBehaviorTrait
{
    /**
     * @return array
     * @inheritdoc
     */
    public function behaviors()
    {
        return $this->traceableBehaviors();
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function traceableBehaviors()
    {
        /** @var BaseActiveRecord $this */
        $behaviors = [
            'timestamp' => [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => $this->hasAttribute('created_at') ? 'created_at' : false,
                'updatedAtAttribute' => $this->hasAttribute('updated_at') ? 'updated_at' : false,
            ],
            'blameable' => [
                'class' => BlameableBehavior::class,
                'createdByAttribute' => $this->hasAttribute('created_by') ? 'created_by' : false,
                'updatedByAttribute' => $this->hasAttribute('updated_by') ? 'updated_by' : false,
                'defaultValue' => 0,
            ],
        ];
        $behaviors = array_filter($behaviors, function($behavior) {
            //return available attributes, if not empty, enable behavior
            return array_filter($behavior, function($k, $v){
                return strpos($k, 'Attribute') !== 0 && $v;
            }, ARRAY_FILTER_USE_BOTH);
        });
        return $behaviors;
    }
}
