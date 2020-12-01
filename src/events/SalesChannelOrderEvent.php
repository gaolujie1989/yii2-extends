<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\sales\channel\events;

use lujie\sales\channel\models\SalesChannelOrder;
use yii\base\Event;

/**
 * Class SalesChannelOrderEvent
 * @package lujie\sales\channel\events
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class SalesChannelOrderEvent extends Event
{
    /**
     * @var SalesChannelOrder
     */
    public $salesChannelOrder;

    /**
     * @var array
     */
    public $externalOrder;
}