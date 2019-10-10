<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\project\controllers\backend;

use lujie\extend\rest\ActiveController;
use lujie\project\models\TaskAttachment;

/**
 * Class TaskAttachmentController
 * @package lujie\project\controllers\backend
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class TaskAttachmentController extends ActiveController
{
    public $modelClass = TaskAttachment::class;
}
