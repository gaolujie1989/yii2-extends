<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\auth\controllers\rest;

use lujie\auth\forms\AuthPermissionForm;
use lujie\auth\models\AuthItem;
use lujie\auth\searches\AuthPermissionSearch;
use lujie\extend\rest\ActiveController;
use Yii;

/**
 * Class RoleController
 * @package lujie\auth\controllers\rest
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class PermissionController extends ActiveController
{
    public $modelClass = AuthItem::class;

    public $formClass = AuthPermissionForm::class;

    public $searchClass = AuthPermissionSearch::class;

    /**
     * @return array
     * @inheritdoc
     */
    public function actionTree(): array
    {
        return Yii::$app->params['permissions'] ?? [];
    }
}