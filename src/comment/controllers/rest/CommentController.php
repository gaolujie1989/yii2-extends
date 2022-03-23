<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\common\comment\controllers\rest;

use lujie\common\comment\models\Comment;
use lujie\extend\rest\ActiveController;

/**
 * Class CommentController
 * @package lujie\common\comment\controllers\rest
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class CommentController extends ActiveController
{
    public $modelClass = Comment::class;
}