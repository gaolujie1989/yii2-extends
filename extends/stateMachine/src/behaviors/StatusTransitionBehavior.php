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
use yii\db\BaseActiveRecord;

/**
 * Class StatusTransitionBehavior
 *
 * @property BaseActiveRecord $owner
 *
 * @package lujie\state\machine
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class StatusTransitionBehavior extends Behavior
{
    /**
     * @var string
     */
    public $statusTransitionClass;

    /**
     * @var bool
     */
    public $runValidation = false;

    /**
     * @return array
     * @inheritdoc
     */
    public function events(): array
    {
        return [
            StateMachineBehavior::EVENT_AFTER_CHANGE_STATUS => 'saveStatusTransition',
        ];
    }

    /**
     * @param StatusEvent $event
     * @inheritdoc
     */
    public function saveStatusTransition(StatusEvent $event): void
    {
        /** @var BaseActiveRecord $statusTransition */
        $statusTransition = new $this->statusTransitionClass;
        $statusTransition->setAttributes([
            'oldStatus' => $event->oldStatus,
            'newStatus' => $event->newStatus,
            'statusModel' => $event->sender,
        ]);
        $statusTransition->save($this->runValidation);
    }
}
