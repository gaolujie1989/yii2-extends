<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\common\address\controllers\rest;

use lujie\batch\BatchAction;
use lujie\common\address\forms\AddressPostalCodeBatchForm;
use lujie\common\address\forms\AddressPostalCodeCreateForm;
use lujie\common\address\models\AddressPostalCode;
use lujie\extend\rest\ActiveController;

/**
 * Class AddressPostalCodeController
 * @package lujie\common\address\controllers\rest
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class AddressPostalCodeController extends ActiveController
{
    public $modelClass = AddressPostalCode::class;

    public $uploadPath = '@uploads/temp';

    /**
     * @return array
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function actions(): array
    {
        $actions = parent::actions();
        $actions['create']['modelClass'] = AddressPostalCodeCreateForm::class;
        return array_merge($actions, [
            'batch-update' => [
                'class' => BatchAction::class,
                'modelClass' => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
                'batchFormClass' => AddressPostalCodeBatchForm::class,
                'method' => 'batchUpdate'
            ],
        ]);
    }
}