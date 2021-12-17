<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\db;

use Yii;
use yii\base\Event;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\BaseActiveRecord;

/**
 * Trait TraceableBehaviorTrait
 *
 * @property int $created_at
 * @property int $created_by
 * @property int $updated_at
 * @property int $updated_by
 *
 * @property bool $saveEmptyOnUpdateBy
 *
 * @package lujie\extend\db
 */
trait TraceableBehaviorTrait
{
    /**
     * @return array
     * @inheritdoc
     */
    public function behaviors(): array
    {
        return array_merge(parent::behaviors(), $this->traceableBehaviors());
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function traceableBehaviors(): array
    {
        if (Yii::$app->has('activeRecordTracer') || $this->hasActiveRecordTracer()) {
            return [];
        }
        if (strpos(static::class, 'ActiveRecord') !== false) {
            return [];
        }
        $behaviors = [];
        /** @var BaseActiveRecord $this */
        if ($this->hasAttribute('created_at') || $this->hasAttribute('updated_at')) {
            $behaviors['timestampTrace'] = [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => $this->hasAttribute('created_at') ? 'created_at' : false,
                'updatedAtAttribute' => $this->hasAttribute('updated_at') ? 'updated_at' : false,
            ];
        }
        if ($this->hasAttribute('created_by') || $this->hasAttribute('updated_by')) {
            $behaviors['blameableTrace'] = [
                'class' => BlameableBehavior::class,
                'createdByAttribute' => $this->hasAttribute('created_by') ? 'created_by' : false,
                'updatedByAttribute' => $this->hasAttribute('updated_by') ? 'updated_by' : false,
                'defaultValue' => [$this, 'getActionByDefault'],
            ];
        }
        return $behaviors;
    }

    /**
     * @param Event $event
     * @return int
     * @inheritdoc
     */
    public function getActionByDefault(Event $event): int
    {
        return (isset($this->saveEmptyOnUpdateBy) && $this->saveEmptyOnUpdateBy)
            ? 0
            : ($event->sender->updated_by ?? 0);
    }

    /**
     * @return bool
     * @inheritdoc
     */
    public function hasActiveRecordTracer(): bool
    {
        $components = Yii::$app->getComponents(false);
        foreach ($components as $component) {
            if ($component instanceof ActiveRecordTracer) {
                return true;
            }
        }
        return false;
    }
}
