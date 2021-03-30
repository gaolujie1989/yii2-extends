<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\eav\behaviors;

use lujie\ar\relation\behaviors\RelationDeletableBehavior;
use lujie\ar\relation\behaviors\RelationSavableBehavior;
use lujie\eav\models\ModelText;
use lujie\eav\models\ModelValueQuery;
use lujie\extend\helpers\ClassHelper;
use yii\base\Behavior;
use yii\db\ActiveQuery;
use yii\db\BaseActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * Class ModelValueBehavior
 * @package lujie\eav\behaviors
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ModelValueBehavior extends Behavior
{
    /**
     * @var string
     */
    public $modelType = '';

    /**
     * @var string
     */
    public $noUpdateValue = 'NOUPDATE';

    /**
     * @var array
     */
    public $keys = [];

    /**
     * @var array
     */
    public $channels = [];

    /**
     * @var string
     */
    public $defaultValue = '';

    /**
     * @var string
     */
    public $valueName = 'Texts';

    /**
     * @var string
     */
    public $relationKey = 'modelTexts';

    /**
     * @var string
     */
    public $modelClass = ModelText::class;

    /**
     * @var bool
     */
    public $attachRelationBehaviors = true;

    #region relation behaviors

    /**
     * @param \yii\base\Component $owner
     * @inheritdoc
     */
    public function attach($owner)
    {
        parent::attach($owner);
        if (empty($this->modelType)) {
            $this->modelType = ClassHelper::getClassShortName($this->owner);
        }
        if ($this->attachRelationBehaviors) {
            $owner->attachBehaviors($this->relationBehaviors());
        }
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function relationBehaviors(): array
    {
        return [
            'relationSave' . $this->valueName => [
                'class' => RelationSavableBehavior::class,
                'relations' => [$this->relationKey],
                'indexKeys' => [
                    $this->relationKey => static function ($text) {
                        return $text['key'] . '-' . $text['channel'];
                    },
                ],
            ],
            'relationDelete' . $this->valueName => [
                'class' => RelationDeletableBehavior::class,
                'relations' => [$this->relationKey],
            ]
        ];
    }

    #endregion

    #region mock value relation query method

    /**
     * @param string $name
     * @return bool
     */
    protected function isValueName(string $name): bool
    {
        return strtolower($name) === strtolower($this->valueName);
    }

    /**
     * @param string $name
     * @return bool
     */
    protected function isRelationMethod(string $name): bool
    {
        return strpos($name, 'get') === 0 && strtolower(substr($name, 3)) === strtolower($this->relationKey);
    }

    /**
     * @param string $name
     * @return bool
     */
    protected function isRelationName(string $name): bool
    {
        return strtolower($name) === strtolower($this->relationKey);
    }

    public function __call($name, $params)
    {
        if ($this->isRelationMethod($name)) {
            return $this->getModelValues();
        }
        parent::__call($name, $params);
    }

    public function __get($name)
    {
        if ($this->isValueName($name)) {
            return $this->getValues();
        } elseif ($this->isRelationName($name)) {
            return $this->getModelValues();
        }
        return parent::__get($name);
    }

    public function __set($name, $value)
    {
        if ($this->isValueName($name)) {
            $this->setValues($value);
        } else {
            parent::__set($name, $value);
        }
    }

    /**
     * @param string $name
     * @return bool
     * @inheritdoc
     */
    public function hasMethod($name)
    {
        if ($this->isRelationMethod($name)) {
            return true;
        }
        return parent::hasMethod($name);
    }

    /**
     * @param string $name
     * @param bool $checkVars
     * @return bool
     * @inheritdoc
     */
    public function canGetProperty($name, $checkVars = true)
    {
        if ($this->isValueName($name)) {
            return true;
        }
        if ($this->isRelationName($name)) {
            return true;
        }
        return parent::canGetProperty($name, $checkVars);
    }

    /**
     * @param string $name
     * @param bool $checkVars
     * @return bool
     * @inheritdoc
     */
    public function canSetProperty($name, $checkVars = true)
    {
        if ($this->isValueName($name)) {
            return true;
        }
        return parent::canSetProperty($name, $checkVars);
    }

    #endregion

    #region value attributes

    /**
     * @return ActiveQuery|ModelValueQuery
     * @inheritdoc
     */
    protected function getModelValues(): ActiveQuery
    {
        /** @var BaseActiveRecord $owner */
        $owner = $this->owner;
        $primaryKey = $owner::primaryKey();
        return $owner->hasMany($this->modelClass, ['model_id' => $primaryKey[0]])
            ->andOnCondition(['model_type' => $this->modelType]);
    }

    /**
     * @return array
     */
    protected function getValues(): array
    {
        $values = ArrayHelper::map($this->owner->{$this->relationKey}, 'key', 'value', 'channel');
        if ($this->keys && $this->channels) {
            $default = array_fill_keys($this->channels, array_fill_keys($this->keys, $this->defaultValue));
            $values = ArrayHelper::merge($default, $values);
        }
        return $values;
    }

    /**
     * @param array $values
     */
    protected function setValues(array $values): void
    {
        $modelValues = [];
        foreach ($this->channels as $channel) {
            $keyValues = $values[$channel] ?? [];
            foreach ($this->keys as $key) {
                $value = $keyValues[$key] ?? null;
                $modelValues[] = (is_array($value) || $value === null || $value === $this->noUpdateValue) ? [
                    'model_type' => $this->modelType,
                    'key' => $key,
                    'channel' => $channel,
                ] : [
                    'model_type' => $this->modelType,
                    'key' => $key,
                    'value' => $value,
                    'channel' => $channel,
                ];
            }
        }
        $this->owner->{$this->relationKey} = $modelValues;
    }

    #endregion
}
