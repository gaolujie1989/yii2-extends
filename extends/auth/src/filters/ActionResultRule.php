<?php
/**
 * @copyright Copyright (c) 2016
 */

namespace lujie\auth\filters;

use yii\base\Action;
use yii\filters\AccessRule;

/**
 * Class ActionResultRule
 * 作为后期扩展预留
 * @package lujie\auth\filters
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ActionResultRule extends ActionAccessRule
{
    /**
     * @var string
     */
    public $suffix = '_result';
}
