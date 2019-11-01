<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\recording\controllers\rest;

use lujie\data\recording\forms\GenerateSourceForm;
use lujie\data\recording\models\DataAccount;
use lujie\extend\rest\ActiveController;
use lujie\extend\rest\MethodAction;

/**
 * Class DataAccountController
 * @package kiwi\data\recording\controllers\backend
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class DataAccountController extends ActiveController
{
    /**
     * @var string|DataAccount
     */
    public $modelClass = DataAccount::class;

    /**
     * @return array
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function actions(): array
    {
        return array_merge(parent::actions(), [
            'generate' => [
                'class' => MethodAction::class,
                'modelClass' => GenerateSourceForm::class,
                'method' => 'generate',
            ]
        ]);
    }
}
