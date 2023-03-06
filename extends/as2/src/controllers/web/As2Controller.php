<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\as2\controllers\web;

use lujie\as2\actions\As2Action;
use yii\web\Controller;

/**
 * Class As2Controller
 * @package lujie\as2\controllers\web
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class As2Controller extends Controller
{
    /**
     * @return array
     * @inheritdoc
     */
    public function actions(): array
    {
        return array_merge(parent::actions(), [
            'index' => [
                'class' => As2Action::class,
            ]
        ]);
    }
}