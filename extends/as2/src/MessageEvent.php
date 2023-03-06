<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\as2;

use AS2\MessageInterface;
use yii\base\Event;

/**
 * Class MessageEvent
 * @package lujie\as2
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class MessageEvent extends Event
{
    /**
     * @var MessageInterface
     */
    public $message;
}