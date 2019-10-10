<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\project\models;


use lujie\upload\models\UploadSavedFile;

/**
 * Class TaskAttachment
 * @package lujie\project\models
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class TaskAttachment extends UploadSavedFile
{
    public const MODEL_TYPE_TASK_ATTACHMENT = 'TASK_ATTACHMENT';

    public const MODEL_TYPE = self::MODEL_TYPE_TASK_ATTACHMENT;
}
