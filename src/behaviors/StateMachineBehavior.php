<?php
/**
 * Created by PhpStorm.
 * User: Lujie
 * Date: 2019/3/18
 * Time: 18:05
 */

namespace lujie\state\machine\behaviors;


use lujie\state\machine\StatusEvent;
use yii\base\Behavior;
use yii\base\InvalidCallException;
use yii\base\ModelEvent;
use yii\db\AfterSaveEvent;
use yii\db\BaseActiveRecord;

/**
 * Class StateMachineBehavior
 *
 * @property BaseActiveRecord $owner
 *
 * @package lujie\state\machine
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class StateMachineBehavior extends Behavior
{
    public const EVENT_BEFORE_CHANGE_STATUS = 'beforeChangeStatus';

    public const EVENT_AFTER_CHANGE_STATUS = 'afterChangeStatus';

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
     *      'method1' => [
     *          'oldStatus1' => 'newStatus1',
     *          'oldStatus2' => 'newStatus2',
     *      ],
     * ]
     * set new status by current status
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
    public function events(): array
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
    public function doAutoSetInitialStatus(): void
    {
        $this->owner->setAttribute($this->statusAttribute, $this->initialStatus);
        $this->initStatusScenario();
    }

    /**
     * @inheritdoc
     */
    public function initStatusScenario(): void
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
    public function beforeSave(ModelEvent $event): void
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
    public function afterSave(AfterSaveEvent $event): void
    {
        if (!isset($event->changedAttributes[$this->statusAttribute])) {
            return;
        }

        $statusEvent = new StatusEvent();
        $statusEvent->oldStatus = $event->changedAttributes[$this->statusAttribute];
        $statusEvent->newStatus = $this->owner->getAttribute($this->statusAttribute);
        $this->owner->trigger(self::EVENT_AFTER_CHANGE_STATUS, $statusEvent);
        $this->initStatusScenario();
    }


    /**
     * @inheritdoc
     */
    public function validateStatus(): bool
    {
        $oldStatus = $this->owner->getOldAttribute($this->statusAttribute) ?: $this->initialStatus;
        $newStatus = $this->owner->getAttribute($this->statusAttribute);

        $transitionStatus = $this->statusTransitions[$oldStatus] ?? [];
        if (in_array($newStatus, $transitionStatus, false)) {
            return true;
        }
        $this->owner->addError($this->statusAttribute, $this->statusInvalidMessage);
        return false;
    }

    /**
     * @param string $name
     * @param array $data
     * @return bool
     * @inheritdoc
     */
    public function callStatusMethod(string $name, $data = []): bool
    {
        if (empty($this->statusMethods[$name])) {
            throw new InvalidCallException('Invalid method name');
        }

        $currentStatus = $this->owner->getAttribute($this->statusAttribute);
        if (empty($this->statusMethods[$name][$currentStatus])) {
            throw new InvalidCallException("Current status {$currentStatus} can not support for method {$name}");
        }

        $newStatus = $this->statusMethods[$name][$currentStatus];
        $this->owner->setAttribute($this->statusAttribute, $newStatus);
        if ($data) {
            $this->owner->setAttributes($data);
        }
        return $this->owner->save();
    }

    /**
     * @param string $name
     * @return bool
     * @inheritdoc
     */
    public function hasMethod($name): bool
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
            return $this->callStatusMethod($name, $params[0] ?? []);
        }
        parent::__call($name, $params);
    }
}
