<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\sales\channel\controllers\rest;

use lujie\extend\rest\ActiveController;
use lujie\sales\channel\models\OttoCategory;

/**
 * Class OttoCategoryController
 * @package lujie\sales\channel\controllers\rest
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class OttoCategoryController extends ActiveController
{
    public $modelClass = OttoCategory::class;

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