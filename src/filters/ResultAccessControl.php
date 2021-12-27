<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\auth\filters;

use Yii;
use yii\base\Action;
use yii\filters\AccessControl;
use yii\filters\AccessRule;

/**
 * Class ResultAccessControl
 * @package lujie\auth\filters
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ResultAccessControl extends AccessControl
{
    /**
     * @param Action $action
     * @return bool
     * @inheritdoc
     */
    public function beforeAction($action): bool
    {
        return true;
    }

    /**
     * @param Action $action
     * @param mixed $result
     * @return mixed
     * @inheritdoc
     */
    public function afterAction($action, $result)
    {
        // * 普通权限，默认没有权限，选一个有一个，匹配规则，匹配成功，返回是否有权限，在控制前之前运行
        // * 数据权限，默认不过滤数据，选一个加一个过滤条件，在控制器之后运行
        $user = $this->user;
        $request = Yii::$app->getRequest();
        /* @var $rule AccessRule */
        foreach ($this->rules as $rule) {
            if (empty($rule->roleParams)) {
                $rule->roleParams = ['result' => $result];
            }
            $rule->allows($action, $user, $request);
        }
        return $result;
    }
}