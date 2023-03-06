<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\executing\monitor\controllers\rest;

use lujie\executing\monitor\models\ExecutableExec;
use lujie\extend\rest\ActiveController;

/**
 * Class ScheduleTaskExecController
 * @package lujie\scheduling\monitor\rest
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ExecutableExecController extends ActiveController
{
    public $modelClass = ExecutableExec::class;

    /**
     * @return array
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function actions(): array
    {
        $actions = parent::actions();
        return array_intersect_key($actions, array_flip(['index']));
    }
}
