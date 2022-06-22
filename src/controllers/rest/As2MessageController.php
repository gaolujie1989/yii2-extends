<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\as2\controllers\rest;

use lujie\as2\forms\As2SendingForm;
use lujie\as2\models\As2Message;
use lujie\extend\rest\ActiveController;
use lujie\extend\rest\MethodAction;

/**
 * Class As2MessageController
 * @package lujie\as2\controllers\rest
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class As2MessageController extends ActiveController
{
    public $modelClass = As2Message::class;

    public $uploadPath = '@statics/uploads/as2files';

    public $uploadAllowedExtensions = ['*'];

    /**
     * @return array
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function actions(): array
    {
        $actions = parent::actions();
        $actions['create'] = [
            'class' => MethodAction::class,
            'modelClass' => As2SendingForm::class,
            'method' => 'send'
        ];
        return $actions;
    }
}