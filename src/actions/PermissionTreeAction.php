<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\auth\actions;

use Yii;
use yii\rest\Action;

/**
 * Class PermissionTreeAction
 * @package lujie\auth\actions
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class PermissionTreeAction extends Action
{
    /**
     * @return array
     * @inheritdoc
     */
    public function run(): array
    {
        return Yii::$app->params['permissions'] ?: [];
    }
}