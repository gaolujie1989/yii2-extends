<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\scheduling;

use lujie\data\loader\ActiveRecordDataLoader;
use lujie\scheduling\monitor\models\ScheduleTask;
use lujie\scheduling\monitor\models\ScheduleTaskQuery;

/**
 * Class ScheduleTaskLoader
 *
 * @property ScheduleTaskQuery $query
 *
 * @package lujie\scheduling
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ScheduleTaskLoader extends ActiveRecordDataLoader
{
    public $modelClass = ScheduleTask::class;

    public $indexBy = 'task_code';

    public $returnAsArray = true;

    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        $this->query->active()->select([
            'executable',
            'expression', 'timezone',
            'shouldLocked' => 'should_locked', 'mutex', 'timeout',
            'shouldQueued' => 'should_queued', 'queue', 'ttr', 'attempts'
        ]);
    }
}
