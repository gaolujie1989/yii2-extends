<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\project\forms;

use lujie\project\models\TaskAttachment;
use lujie\upload\forms\UploadModelFileForm;

/**
 * Class TaskAttachmentForm
 * @package lujie\project\forms
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class TaskAttachmentForm extends UploadModelFileForm
{
    public const MODEL_TYPE = TaskAttachment::MODEL_TYPE_TASK_ATTACHMENT;
}
