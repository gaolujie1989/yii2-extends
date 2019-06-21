<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\queuing\monitor\tests\unit\mocks;

use Yii;
use yii\base\BaseObject;
use yii\base\Exception;
use yii\queue\JobInterface;

/**
 * Class TestJob
 * @package lujie\queuing\monitor\tests\unit\mocks
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class TestJob extends BaseObject implements JobInterface
{
    public $yKey = 'xxx';

    public $yValue = 'xxx';

    public $sleep = 0;

    public $throwEx = false;

    /**
     * @param \yii\queue\Queue $queue
     * @throws Exception
     * @inheritdoc
     */
    public function execute($queue)
    {
        if ($this->throwEx) {
            throw new Exception('Error');
        }
        Yii::$app->params[$this->yKey] = $this->yValue;
        if ($this->sleep) {
            sleep($this->sleep);
        }
    }
}
