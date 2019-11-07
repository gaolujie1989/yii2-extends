<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\recording\jobs;

use lujie\data\loader\DataLoaderInterface;
use lujie\data\recording\forms\RecordingForm;
use lujie\executing\Executor;
use yii\base\BaseObject;
use yii\base\InvalidConfigException;
use yii\queue\JobInterface;
use yii\queue\RetryableJobInterface;

/**
 * Class RecordingJob
 * @package kiwi\data\recording\jobs
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class RecordingJob extends BaseObject implements JobInterface, RetryableJobInterface
{
    /**
     * @var DataLoaderInterface
     */
    public $dataRecorderLoader = 'dataRecorderLoader';

    /**
     * @var int
     */
    public $dataSourceId;

    /**
     * @var Executor
     */
    public $executor;

    /**
     * @var int
     */
    public $attempt = 1;

    /**
     * @param \yii\queue\Queue $queue
     * @return bool|mixed|void
     * @throws InvalidConfigException
     * @throws \Throwable
     * @throws \yii\base\NotSupportedException
     * @inheritdoc
     */
    public function execute($queue)
    {
        $form = new RecordingForm();
        $form->dataRecorderLoader = $this->dataRecorderLoader;
        $form->dataSourceId = $this->dataSourceId;
        $form->executor = $this->executor;
        if ($form->recording()) {
            return true;
        }
        throw new InvalidConfigException(implode(';', $form->getErrorSummary(true)));
    }

    /**
     * @var int
     */
    public $ttr = 1800;

    /**
     * @return int
     * @inheritdoc
     */
    public function getTtr(): int
    {
        return $this->ttr;
    }

    /**
     * @param int $attempt
     * @param \Exception|\Throwable $error
     * @return bool
     * @inheritdoc
     */
    public function canRetry($attempt, $error): bool
    {
        return $attempt < $this->attempt;
    }
}
