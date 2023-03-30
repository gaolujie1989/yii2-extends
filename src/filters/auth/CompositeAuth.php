<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\filters\auth;

/**
 * Class CompositeAuth
 * @package lujie\auth\filters\auth
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class CompositeAuth extends \yii\filters\auth\CompositeAuth
{
    /**
     * @var \yii\base\Action
     * @see \yii\filters\auth\CompositeAuth::authenticate()
     * if (isset($this->owner->action) && $auth->isActive($this->owner->action))
     * if behavior attached to Application, $this->owner is Application, action will be null
     * set action in beforeAction, then it will be available in authenticate
     */
    public $action;

    public function beforeAction($action): bool
    {
        $this->action = $action;
        return parent::beforeAction($action);
    }
}