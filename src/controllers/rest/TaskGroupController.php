<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\project\controllers\rest;

use lujie\extend\rest\ActiveController;
use lujie\project\models\TaskGroup;

/**
 * Class ProjectController
 * @package lujie\project\controllers\backend
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class TaskGroupController extends ActiveController
{
    public $modelClass = TaskGroup::class;
}
