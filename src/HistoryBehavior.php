<?php
/**
 * @copyright Copyright (c) 2017
 */

namespace lujie\arhistory;


use Yii;
use yii\base\Behavior;
use yii\base\Event;
use yii\db\BaseActiveRecord;
use yii\db\AfterSaveEvent;
use yii\db\Exception;
use yii\web\User;

/**
 * Class HistoryBehaviors
 *
 * only save update history detail, for delete, only log delete message
 *
 * @property BaseActiveRecord $owner
 *
 * @package lujie\core\behaviors
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class HistoryBehavior extends Behavior
{
    const EVENT_INSERT = 1;

    const EVENT_UPDATE = 2;

    const EVENT_DELETE = 3;

    public $allowEvents = [
        self::EVENT_INSERT,
        self::EVENT_UPDATE,
        self::EVENT_DELETE,
    ];

    protected $eventMap = [
        self::EVENT_INSERT => BaseActiveRecord::EVENT_AFTER_INSERT,
        self::EVENT_UPDATE => BaseActiveRecord::EVENT_AFTER_UPDATE,
        self::EVENT_DELETE => BaseActiveRecord::EVENT_AFTER_DELETE,
    ];

    /**
     * @var array List of attributes which not need track at updating. Apply only for `self::EVENT_UPDATE`.
     */
    public $skipAttributes = ['created_at', 'created_by', 'updated_at', 'updated_by'];

    /**
     * @var array List of custom attributes which which are a pair of `key`=>`value` where `key` is attribute name and
     * `value` it anonymous callback function of attribute. Function will be apply for old and value information data.
     * Example:
     * ```php
     * [
     *      'attribute_1' => function($event, $isNewValue) {
     *          if ($isNewValue) {
     *              return $event->sender->attribute_1;
     *          }
     *          return $event->changedAttributes['attribute_1'];
     *      },
     * ]
     * ```
     *  Apply only for `self::EVENT_UPDATE`.
     */
    public $customAttributes = [];

    /**
     * @var array
     */
    public $historyAttributesCallback = [];

    /**
     * @var string
     */
    public $historyModelClass = 'lujie\arhistory\models\History';

    /**
     * @var string
     */
    public $historyDetailModelClass = 'lujie\arhistory\models\HistoryDetail';

    /**
     * @inheritdoc
     */
    public function events()
    {
        $events = [];
        foreach ($this->allowEvents as $name) {
            $events[$this->eventMap[$name]] = 'saveHistory';
        }
        return $events;
    }

    /**
     * Evaluates the attribute value and assigns it to the current attributes.
     * @param Event $event
     * @throws Exception
     * @throws \Throwable
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function saveHistory(Event $event)
    {
        if ($event->name == BaseActiveRecord::EVENT_AFTER_UPDATE && !$this->getDirtyAttributes($event)) {
            return;
        }

        $eventMap = array_flip($this->eventMap);

        $owner = $this->owner;
        $tableName = '';
        if ($owner instanceof yii\db\ActiveRecord) {
            $tableName = $owner::tableName();
        } else if (class_exists('yii\mongodb\ActiveRecord') && $owner instanceof yii\mongodb\ActiveRecord) {
            $tableName = $owner::collectionName();
        }
        $data = [
            'event' => $eventMap[$event->name],
            'table_name' => $tableName,
            'row_id' => $owner->getPrimaryKey(),
            'custom_data' => $this->getCustomData($event),
        ];

        /** @var BaseActiveRecord $history */
        $history = new $this->historyModelClass();
        $history->setAttributes($data);
        foreach ($this->historyAttributesCallback as $attribute => $callback) {
            $history->setAttribute($attribute, call_user_func($callback, $event));
        }

        if (!$history->save()) {
            throw new Exception('Save History Failed.', $history->getErrors());
        }

        // if update, log details
        if ($event->name == BaseActiveRecord::EVENT_AFTER_UPDATE && $event instanceof AfterSaveEvent) {
            foreach ($event->changedAttributes as $attribute => $value) {
                if (in_array($attribute, $this->skipAttributes) || $value == $owner->$attribute) {
                    continue;
                }

                if (isset($this->customAttributes[$attribute])) {
                    $oldValue = call_user_func($this->customAttributes[$attribute], $event, false);
                    $newValue = call_user_func($this->customAttributes[$attribute], $event, true);
                } else {
                    $oldValue = $value;
                    $newValue = $owner->$attribute;
                }

                /** @var BaseActiveRecord $historyDetail */
                $historyDetail = new $this->historyDetailModelClass();
                $data = [
                    'history_id' => $history->getPrimaryKey(),
                    'field_name' => $attribute,
                    'old_value' => is_array($oldValue) ? json_encode($oldValue) : $oldValue,
                    'new_value' => is_array($newValue) ? json_encode($newValue) : $newValue,
                ];
                $historyDetail->setAttributes($data);
                if (!$historyDetail->save()) {
                    throw new Exception('Save History Detail Failed.', $historyDetail->getErrors());
                }
            }
        }
    }

    /**
     * @param Event $event
     * @return array
     * @throws \Throwable
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function getCustomData(Event $event)
    {
        /** @var User $user */
        $user = Yii::$app->get('user', false);
        /** @var BaseActiveRecord $identity */
        $identity = $user ? $user->getIdentity() : null;
        $username = 'Unknown';
        if ($identity) {
            $username = $identity->hasAttribute('username') ? $identity->getAttribute('username') : $identity->getPrimaryKey();
        }
        return [
            'username' => $username,
            'dirtyAttributes' => $this->getDirtyAttributes($event, true),
        ];
    }

    /**
     * @param Event $event
     * @param bool $returnLabel
     * @return array
     * @inheritdoc
     */
    public function getDirtyAttributes(Event $event, $returnLabel = false)
    {
        $dirtyAttributeLabels = [];
        if ($event->name == BaseActiveRecord::EVENT_AFTER_UPDATE && $event instanceof AfterSaveEvent) {
            /** @var BaseActiveRecord $sender */
            $sender = $event->sender;
            foreach ($event->changedAttributes as $attribute => $value) {
                if (in_array($attribute, $this->skipAttributes) || $value == $sender->$attribute) {
                    continue;
                }
                $dirtyAttributeLabels[] = $returnLabel ? $sender->getAttributeLabel($attribute) : $attribute;
            }
        }
        return $dirtyAttributeLabels;
    }
}