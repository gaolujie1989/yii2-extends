<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\project\controllers\backend;

use lujie\extend\rest\ActiveController;
use lujie\project\models\Task;

/**
 * Class ProjectController
 * @package lujie\project\controllers\backend
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class TaskController extends ActiveController
{
    public $modelClass = Task::class;
}
