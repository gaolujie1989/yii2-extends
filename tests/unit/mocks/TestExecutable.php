<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\executing\tests\unit\mocks;


use lujie\executing\ExecutableInterface;
use lujie\executing\ExecutableTrait;
use lujie\executing\LockableInterface;
use lujie\executing\LockableTrait;
use lujie\executing\QueueableInterface;
use lujie\executing\QueueableTrait;
use Yii;
use yii\base\BaseObject;

/**
 * Class TestExecutable
 * @package lujie\executing\tests\unit\mocks
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class TestExecutable extends BaseObject implements ExecutableInterface, LockableInterface, QueueableInterface
{
    use ExecutableTrait, LockableTrait, QueueableTrait;

    public $yKey = 'xxx';

    public $yValue = 'xxx';

    /**
     * @return mixed|void
     * @inheritdoc
     */
    public function execute()
    {
        Yii::$app->params[$this->yKey] = $this->yValue;
        //for testing lockable, if lockable, set shouldLock to false then mutex key will not release
        if ($this->shouldLocked) {
            $this->shouldLocked = false;
        }
    }
}
