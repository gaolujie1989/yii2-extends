<?php
/**
 * @copyright Copyright (c) 2023
 */

namespace lujie\common\history\controllers\rest;

use lujie\common\history\models\ModelHistory;
use lujie\extend\rest\ActiveController;

/**
 * Class HistoryController
 * @package lujie\common\history\controllers\rest
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class HistoryController extends ActiveController
{
    public $modelClass = ModelHistory::class;

    /**
     * @return array
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function actions(): array
    {
        return array_intersect_key(parent::actions(), ['index']);
    }
}
