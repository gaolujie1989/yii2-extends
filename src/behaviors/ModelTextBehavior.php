<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\eav\behaviors;

use lujie\ar\relation\behaviors\RelationDeletableBehavior;
use lujie\ar\relation\behaviors\RelationSavableBehavior;
use lujie\eav\models\ModelText;
use lujie\eav\models\ModelTextQuery;
use yii\base\Behavior;
use yii\db\ActiveQuery;
use yii\db\BaseActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * Class KeyTextBehavior
 * @package lujie\eav\behaviors
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ModelTextBehavior extends Behavior
{
    public $modelType = '';

    public $noUpdateValue = 'NOUPDATE';

    public $keys = [];

    public $channels = [];

    public $relationKey = 'modelTexts';

    /**
     * @return array
     * @inheritdoc
     */
    public function relations(): array
    {
        return [
            'relationSaveText' => [
                'class' => RelationSavableBehavior::class,
                'relations' => [$this->relationKey],
                'indexKeys' => [
                    'modelTexts' => static function ($text) {
                        return $text['key'] . '-' . $text['channel'];
                    },
                ],
                'linkUnlinkRelations' => [$this->relationKey]
            ],
            'relationDeleteText' => [
                'class' => RelationDeletableBehavior::class,
                'relations' => [$this->relationKey],
            ]
        ];
    }

    /**
     * @return ActiveQuery|ModelTextQuery
     * @inheritdoc
     */
    public function getModelTexts(): ActiveQuery
    {
        /** @var BaseActiveRecord $owner */
        $owner = $this->owner;
        $primaryKey = $owner::primaryKey();
        return $owner->hasMany(ModelText::class, ['model_id' => $primaryKey[0]])->andOnCondition(['model_type' => $this->modelType]);
    }

    /**
     * @return array
     */
    public function getTexts(): array
    {
        $texts = ArrayHelper::map($this->owner->{$this->relationKey}, 'key', 'value', 'channel');
        if ($this->keys && $this->channels) {
            $default = array_fill_keys($this->channels, array_fill_keys($this->keys, ''));
            $texts = ArrayHelper::merge($default, $texts);
        }
        return $texts;
    }

    /**
     * @param array $texts
     */
    public function setTexts(array $texts): void
    {
        $modelTexts = [];
        foreach ($this->channels as $channel) {
            if (empty($texts[$channel])) {
                continue;
            }
            $textValues = $texts[$channel];
            foreach ($this->keys as $key) {
                if (is_array($textValues[$key])) {
                    continue;
                }
                $modelTexts[] = ($textValues[$key] === $this->noUpdateValue) ? [
                    'key' => $key,
                    'channel' => $channel,
                ] : [
                    'key' => $key,
                    'text' => $textValues[$key],
                    'channel' => $channel,
                ];
            }
        }
        $this->owner->{$this->relationKey} = $modelTexts;
    }
}