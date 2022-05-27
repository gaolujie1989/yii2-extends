<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\common\option\controllers\rest;

use lujie\common\option\actions\OptionListAction;
use lujie\common\option\models\Option;
use lujie\common\option\searches\OptionListSearch;
use lujie\extend\rest\ActiveController;
use lujie\extend\rest\MethodAction;

/**
 * Class CommentController
 * @package lujie\common\comment\controllers\rest
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class OptionController extends ActiveController
{
    public $modelClass = Option::class;

    /**
     * @var string
     */
    public $listClass = OptionListSearch::class;

    /**
     * @return array
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function actions(): array
    {
        return array_merge(parent::actions(), [
            'list' => [
                'class' => MethodAction::class,
                'modelClass' => $this->listClass,
                'method' => 'getOptions',
            ],
        ]);
    }
}