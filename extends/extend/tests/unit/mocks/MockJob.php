<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\tests\unit\mocks;

use yii\base\BaseObject;
use yii\queue\JobInterface;

/**
 * Class MockJob
 * @package lujie\extend\tests\unit\mocks
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class MockJob extends BaseObject implements JobInterface
{
    /**
     * @param \yii\queue\Queue $queue
     * @return mixed|void
     * @inheritdoc
     */
    public function execute($queue)
    {
        // TODO: Implement execute() method.
    }
}
