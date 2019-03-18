<?php
/**
 * Created by PhpStorm.
 * User: Lujie
 * Date: 2019/3/18
 * Time: 18:05
 */

namespace lujie\statemachine;


use yii\base\Behavior;
use yii\base\ModelEvent;
use yii\db\AfterSaveEvent;
use yii\db\BaseActiveRecord;

/**
 * Class StateMachineBehavior
 *
 * @property BaseActiveRecord $owner
 *
 * @package lujie\statemachine
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class StateMachineBehavior extends Behavior
{
    const EVENT_BEFORE_CHANGE_STATUS = 'beforeChangeStatus';

    const EVENT_AFTER_CHANGE_STATUS = 'afterChangeStatus';

    /**
     * @var string
     */
    public $statusAttribute = 'status';

    /**
     * @var mixed
     */
    public $initialStatus;

    /**
     * @var bool
     */
    public $autoSetInitialStatus = false;

    /**
     * [
     *      'status1' => ['status2', 'status3'],
     *      'status2' => ['status3'],
     * ]
     * @var array
     */
    public $statusTransitions = [];

    /**
     * [
     *      'status1' => 'scenario1'
     * ]
     * @var array
     */
    public $statusScenarios = [];

    /**
     * [
     *      'method1' => ['oldStatus', 'newStatus'],
     * ]
     * @var array
     */
    public $statusMethods = [];

    /**
     * @var string
     */
    public $statusInvalidMessage = 'Status is invalid';

    /**
     * @return array
     * @inheritdoc
     */
    public function events()
    {
        return [
            BaseActiveRecord::EVENT_INIT => 'doAutoSetInitialStatus',
            BaseActiveRecord::EVENT_AFTER_FIND => 'initStatusScenario',
            BaseActiveRecord::EVENT_BEFORE_INSERT => 'beforeSave',
            BaseActiveRecord::EVENT_BEFORE_UPDATE => 'beforeSave',
            BaseActiveRecord::EVENT_AFTER_INSERT => 'afterSave',
            BaseActiveRecord::EVENT_AFTER_UPDATE => 'afterSave',
        ];
    }

    /**
     * @inheritdoc
     */
    public function doAutoSetInitialStatus()
    {
        $this->owner->setAttribute($this->statusAttribute, $this->initialStatus);
        $this->initStatusScenario();
    }

    /**
     * @inheritdoc
     */
    public function initStatusScenario()
    {
        $status = $this->owner->getAttribute($this->statusAttribute);
        if (isset($this->statusScenarios[$status])) {
            $this->owner->setScenario($this->statusScenarios[$status]);
        }
    }

    /**
     * @param ModelEvent $event
     * @inheritdoc
     */
    public function beforeSave(ModelEvent $event)
    {
        $oldStatus = $this->owner->getOldAttribute($this->statusAttribute) ?: $this->initialStatus;
        $newStatus = $this->owner->getAttribute($this->statusAttribute);
        if ($oldStatus === $newStatus) {
            return;
        }

        if (!$this->validateStatus()) {
            $event->isValid = false;
            return;
        }

        $statusEvent = new StatusEvent();
        $statusEvent->oldStatus = $oldStatus;
        $statusEvent->newStatus = $newStatus;
        $this->owner->trigger(self::EVENT_BEFORE_CHANGE_STATUS, $statusEvent);
        if (!$statusEvent->isValid) {
            $event->isValid = false;
            return;
        }
    }

    /**
     * @param AfterSaveEvent $event
     * @inheritdoc
     */
    public function afterSave(AfterSaveEvent $event)
    {
        if (!isset($event->changedAttributes[$this->statusAttribute])) {
            return;
        }

        $statusEvent = new StatusEvent();
        $statusEvent->oldStatus = $event->changedAttributes[$this->statusAttribute];
        $statusEvent->newStatus = $this->owner->getAttribute($this->statusAttribute);
        $this->owner->trigger(self::EVENT_AFTER_CHANGE_STATUS, $statusEvent);
    }


    /**
     * @inheritdoc
     */
    public function validateStatus()
    {
        $oldStatus = $this->owner->getOldAttribute($this->statusAttribute) ?: $this->initialStatus;
        $newStatus = $this->owner->getAttribute($this->statusAttribute);

        $transitionStatus = $this->statusTransitions[$oldStatus] ?? [];
        if (in_array($newStatus, $transitionStatus)) {
            return true;
        } else {
            $this->owner->addError($this->statusAttribute, $this->statusInvalidMessage);
            return false;
        }
    }

    /**
     * @param string $name
     * @return bool
     * @inheritdoc
     */
    public function hasMethod($name)
    {
        if (isset($this->statusMethods[$name])) {
            return true;
        }
        return parent::hasMethod($name);
    }

    /**
     * @param string $name
     * @param array $params
     * @return bool|mixed
     * @inheritdoc
     */
    public function __call($name, $params)
    {
        if (isset($this->statusMethods[$name])) {
            [$oldStatus, $newStatus] = $this->statusMethods[$name];
            $currentStatus = $this->owner->getAttribute($this->statusAttribute);
            if ($oldStatus !== $currentStatus) {
                $this->owner->addError($this->statusAttribute, $this->statusInvalidMessage);
                return false;
            }
            $this->owner->setAttribute($this->statusAttribute, $newStatus);
            if (isset($params[0]) && is_array($params[0])) {
                $this->owner->setAttributes($params[0]);
            }
            $this->owner->save();
        }
        parent::__call($name, $params);
    }
}
