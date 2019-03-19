<?php
/**
 * Created by PhpStorm.
 * User: Lujie
 * Date: 2019/3/18
 * Time: 22:57
 */

namespace lujie\statemachine;


use yii\base\ModelEvent;

/**
 * Class StatusEvent
 * @package lujie\statemachine
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class StatusEvent extends ModelEvent
{
    public $oldStatus;

    public $newStatus;
}
