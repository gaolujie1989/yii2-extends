<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\project\controllers\rest;

use lujie\extend\rest\ActiveController;
use lujie\project\models\Project;

/**
 * Class ProjectController
 * @package lujie\project\controllers\backend
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ProjectController extends ActiveController
{
    public $modelClass = Project::class;
}
