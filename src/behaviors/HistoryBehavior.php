<?php
/**
 * @copyright Copyright (c) 2017
 */

namespace lujie\ar\history\behaviors;


use lujie\ar\history\handlers\ArrayAttributeHistoryHandler;
use lujie\ar\history\handlers\AttributeHistoryHandlerInterface;
use lujie\ar\history\handlers\BaseAttributeHistoryHandler;
use lujie\ar\history\models\History;
use yii\base\Behavior;
use yii\base\InvalidConfigException;
use yii\base\ModelEvent;
use yii\db\AfterSaveEvent;
use yii\db\BaseActiveRecord;
use yii\db\Exception;
use yii\di\Instance;
use yii\helpers\ArrayHelper;

/**
 * Class HistoryBehaviors
 *
 * @property BaseActiveRecord $owner
 *
 * @package lujie\core\behaviors
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class HistoryBehavior extends Behavior
{
    /**
     * @var string
     */
    public $historyModelClass = History::class;

    /**
     * @var string
     */
    public $modelType;

    /**
     * @var string
     */
    public $parentIdAttribute;

    /**
     * @var array
     */
    public $attributes = [];

    /**
     * @var array
     */
    public $attributeHandlers = [];

    /**
     * @var array
     */
    private $oldAttributeValues = [];

    /**
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        if (empty($this->modelType)) {
            throw new InvalidConfigException('The property `modelType` must be set.');
        }
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function events(): array
    {
        return [
            BaseActiveRecord::EVENT_BEFORE_UPDATE => 'setOldAttributes',
            BaseActiveRecord::EVENT_AFTER_UPDATE => 'handleAttributeHistory',
        ];
    }

    /**
     * @param ModelEvent $event
     */
    public function setOldAttributes(ModelEvent $event): void
    {
        /** @var BaseActiveRecord $sender */
        $sender = $event->sender;
        foreach ($this->attributes as $attribute) {
            if ($sender->hasAttribute($attribute)) {
                $this->oldAttributeValues[$attribute] = $sender->getOldAttribute($attribute);
            } else {
                $this->oldAttributeValues[$attribute] = ArrayHelper::getValue($sender, $attribute);
            }
        }
    }

    /**
     * @param AfterSaveEvent $event
     * @throws Exception
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function handleAttributeHistory(AfterSaveEvent $event): void
    {
        /** @var BaseActiveRecord $sender */
        $sender = $event->sender;
        $details = [];
        foreach ($this->attributes as $attribute) {
            $newValue = ArrayHelper::getValue($sender, $attribute);
            $oldValue = $this->oldAttributeValues[$attribute];
            if (isset($this->attributeHandlers[$attribute])) {
                /** @var AttributeHistoryHandlerInterface $handler */
                $handler = Instance::ensure($this->attributeHandlers[$attribute], AttributeHistoryHandlerInterface::class);
            } else {
                $handler = is_array($oldValue) ? new ArrayAttributeHistoryHandler() : new BaseAttributeHistoryHandler();
            }
            $detail = [
                'attribute' => $attribute,
                'oldValue' => $handler->extract($oldValue),
                'newValue' => $handler->extract($newValue),
                'diffValue' => $handler->diff($oldValue, $newValue),
            ];
            $details[$attribute] = $detail;
        }

        $owner = $this->owner;
        /** @var History $history */
        $history = new $this->historyModelClass();
        $history->model_type = $this->modelType;
        $history->model_id = $owner->getPrimaryKey();
        if ($this->parentIdAttribute) {
            $history->parent_id = $owner->getAttribute($this->parentIdAttribute);
        }
        $history->details = $details;
        $history->summary = '';
        if (!$history->save(false)) {
            throw new Exception('Save History Failed.');
        }
    }
}
