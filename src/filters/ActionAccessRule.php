<?php
/**
 * @copyright Copyright (c) 2016
 */

namespace lujie\auth\filters;

use yii\base\Action;
use yii\base\Application;
use yii\filters\AccessRule;

/**
 * Class ActionAccessRule
 * @package lujie\auth\filters
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ActionAccessRule extends AccessRule
{
    /**
     * @var string
     */
    public $glue = '_';

    /**
     * @param Action $action
     * @return string
     * @inheritdoc
     */
    protected function getActionPermission(Action $action): string
    {
        $keys = [$action->controller->id, $action->id];
        $module = $action->controller->module;
        while ($module && !($module instanceof Application)) {
            array_unshift($keys, $module->id);
            $module = $module->module;
        }
        return implode($this->glue, $keys);
    }

    /**
     * @param Action $action
     * @param false|\yii\web\User $user
     * @param \yii\web\Request $request
     * @return bool|null
     * @inheritdoc
     */
    public function allows($action, $user, $request): array
    {
        $this->permissions = $this->permissions ?: [];
        $this->permissions[] = $this->getActionPermission($action);
        return parent::allows($action, $user, $request);
    }
}
