<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\auth\controllers\rest;

use lujie\auth\forms\AuthPermissionForm;
use lujie\auth\forms\AuthRoleForm;
use lujie\auth\models\AuthItem;
use lujie\auth\searches\AuthPermissionSearch;
use lujie\auth\searches\AuthRoleSearch;
use lujie\extend\rest\ActiveController;
use yii\rbac\Item;

/**
 * Class AuthItemController
 * @package lujie\auth\controllers\rest
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class AuthItemController extends ActiveController
{
    public $modelClass = AuthItem::class;

    public $itemType;

    /**
     * @var array
     */
    public $itemTypeClasses = [
        Item::TYPE_ROLE => [
            'form' => AuthRoleForm::class,
            'search' => AuthRoleSearch::class,
        ],
        Item::TYPE_PERMISSION => [
            'form' => AuthPermissionForm::class,
            'search' => AuthPermissionSearch::class,
        ]
    ];

    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        if (isset($this->itemTypeClasses[$this->itemType])) {
            $typeClasses = $this->itemTypeClasses[$this->itemType];
            $this->formClass = $typeClasses['form'];
            $this->searchClass = $typeClasses['search'];
        }
    }
}