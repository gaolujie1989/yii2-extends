<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\auth\controllers\rest;

use lujie\auth\models\AuthRule;
use lujie\extend\rest\ActiveController;

/**
 * Class AuthRuleController
 * @package lujie\auth\controllers\rest
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class AuthRuleController extends ActiveController
{
    public $modelClass = AuthRule::class;

    /**
     * @return array
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function actions(): array
    {
        return array_intersect_key(parent::actions(), array_flip(['index', 'view']));
    }
}