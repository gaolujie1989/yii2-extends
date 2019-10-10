<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\project\forms;

use lujie\project\models\Task;

/**
 * Class TaskForm
 * @package lujie\project\forms
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class TaskForm extends Task
{
    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['project_id', 'task_group_id', 'parent_task_id',
                'priority', 'status', 'owner_id', 'executor_id',
                'due_at', 'started_at', 'finished_at'], 'integer'],
            [['additional'], 'safe'],
            [['name'], 'string', 'max' => 250],
            [['description'], 'string', 'max' => 1000],
        ];
    }
}
