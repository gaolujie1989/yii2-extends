<?php
/**
 * Created by PhpStorm.
 * User: Lujie
 * Date: 2019/3/18
 * Time: 22:57
 */

namespace lujie\state\machine;


use yii\base\ModelEvent;

/**
 * Class StatusEvent
 * @package lujie\state\machine
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class StatusEvent extends ModelEvent
{
    public $oldStatus;

    public $newStatus;
}
