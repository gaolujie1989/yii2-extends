<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\db;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\BaseActiveRecord;
use yii\web\User;

/**
 * Trait TraceableBehaviorTrait
 *
 * @property int $created_at
 * @property int $created_by
 * @property int $updated_at
 * @property int $updated_by
 *
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
        $behaviors = [];
        /** @var BaseActiveRecord $this */
        if ($this->hasAttribute('created_at') || $this->hasAttribute('updated_at')) {
            $behaviors['timestamp'] = [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => $this->hasAttribute('created_at') ? 'created_at' : false,
                'updatedAtAttribute' => $this->hasAttribute('updated_at') ? 'updated_at' : false,
            ];
        }
        if ($this->hasAttribute('created_by') || $this->hasAttribute('updated_by')) {
            $behaviors['blameable'] = [
                'class' => BlameableBehavior::class,
                'createdByAttribute' => $this->hasAttribute('created_by') ? 'created_by' : false,
                'updatedByAttribute' => $this->hasAttribute('updated_by') ? 'updated_by' : false,
                'value' => [$this, 'getActionBy'],
            ];
        }
        return $behaviors;
    }

    public function getActionBy()
    {
        /** @var User $user */
        $user = Yii::$app->get('user', false);
        return $user ? ($user->getIsGuest() ? 0 : $user->getId()) : 0;
    }
}
