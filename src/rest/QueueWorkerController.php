<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\queuing\monitor\rest;


use lujie\core\rest\ActiveController;
use lujie\queuing\monitor\models\QueueWorker;

/**
 * Class QueueWorkerController
 * @package lujie\queuing\monitor\rest
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class QueueWorkerController extends ActiveController
{
    public $modelClass = QueueWorker::class;

    /**
     * @return array
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function actions()
    {
        $actions = parent::actions();
        return array_intersect_key($actions, array_flip(['index']));
    }
}
