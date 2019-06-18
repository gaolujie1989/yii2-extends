<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\ar\snapshoot\behaviors;

use yii\base\Event;
use yii\db\BaseActiveRecord;

/**
 * Class SnapshootEvent
 * @package lujie\ar\snapshoot\behaviors
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class SnapshootEvent extends Event
{
    /**
     * @var bool
     */
    public $created = false;

    /**
     * @var array
     */
    public $changedAttributes = [];

    /**
     * @var BaseActiveRecord|null
     */
    public $snapshoot;
}
