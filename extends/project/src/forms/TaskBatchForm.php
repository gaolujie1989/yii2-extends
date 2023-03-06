<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\project\forms;

use lujie\batch\BatchForm;

/**
 * Class TaskForm
 * @package lujie\project\forms
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class TaskBatchForm extends BatchForm
{
    /**
     * @return array
     * @inheritdoc
     */
    public function attributes(): array
    {
        return [
            'task_group_id', 'priority', 'status', 'owner_id', 'executor_id',
            'due_time', 'started_time', 'finished_time',
        ];
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            [['task_group_id', 'priority', 'status', 'owner_id', 'executor_id'], 'integer'],
            [['due_time', 'started_time', 'finished_time'], 'date'],
        ];
    }
}
