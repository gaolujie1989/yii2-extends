<?php
/**
 * Created by PhpStorm.
 * User: Lujie
 * Date: 2019/3/18
 * Time: 18:05
 */

namespace lujie\state\machine;


use yii\base\Behavior;
use yii\base\ModelEvent;
use yii\db\AfterSaveEvent;
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
     * @return array
     * @inheritdoc
     */
    public function events(): array
    {
        return [
            StateMachineBehavior::EVENT_AFTER_CHANGE_STATUS => 'logStatusHistory',
        ];
    }

    /**
     * @param StatusEvent $event
     * @inheritdoc
     */
    public function logStatusHistory(StatusEvent $event): void
    {
        /** @var BaseActiveRecord $statusTransition */
        $statusTransition = new $this->statusTransitionClass;
        $statusTransition->setAttributes([
            'oldStatus' => $event->oldStatus,
            'newStatus' => $event->newStatus,
            'statusModel' => $event->sender,
        ]);
        $statusTransition->save();
    }
}
