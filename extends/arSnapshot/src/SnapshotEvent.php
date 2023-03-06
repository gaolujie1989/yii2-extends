<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\ar\snapshot\behaviors;

use yii\base\Event;
use yii\db\BaseActiveRecord;

/**
 * Class SnapshotEvent
 * @package lujie\ar\snapshot\behaviors
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class SnapshotEvent extends Event
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
    public $snapshot;
}
