<?php
/**
 * @copyright Copyright (c) 2023
 */

namespace lujie\extend\simpleFork;

use Jenner\SimpleFork\Runnable;
use lujie\extend\helpers\ComponentHelper;
use Yii;

/**
 * Class YiiProcess
 * @package lujie\extend\simpleFork
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class YiiProcess extends \Jenner\SimpleFork\Process
{
    /**
     * @return array
     * @inheritdoc
     */
    protected function getCallable(): array
    {
        return [$this, 'run'];
    }

    /**
     * @throws \yii\base\ExitException
     * @inheritdoc
     */
    public function run(): void
    {
        ComponentHelper::closeConnections();
        if ($this->runnable instanceof Runnable) {
            $this->runnable->run();
        } else if (is_callable($this->runnable)) {
            call_user_func($this->runnable);
        }
        Yii::$app->end();
    }
}
