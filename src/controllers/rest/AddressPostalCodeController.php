<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\common\address\controllers\rest;

use lujie\batch\BatchAction;
use lujie\common\address\AddressPostalCodeImporter;
use lujie\common\address\models\AddressPostalCode;
use lujie\common\address\forms\AddressPostalCodeBatchForm;
use lujie\common\address\forms\AddressPostalCodeImportForm;
use lujie\data\exchange\actions\FileImportAction;
use lujie\extend\rest\ActiveController;
use lujie\upload\actions\UploadAction;
use lujie\upload\forms\UploadForm;

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
        return array_merge($actions, [
            'upload' => [
                'class' => UploadAction::class,
                'modelClass' => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
                'uploadModel' => [
                    'class' => UploadForm::class,
                    'path' => $this->uploadPath
                ]
            ],
            'import' => [
                'class' => FileImportAction::class,
                'modelClass' => $this->formClass,
                'checkAccess' => [$this, 'checkAccess'],
                'importModel' => [
                    'class' => AddressPostalCodeImportForm::class,
                    'fileImporter' => AddressPostalCodeImporter::class,
                    'path' => $this->uploadPath
                ]
            ],
            'batch-update' => [
                'class' => BatchAction::class,
                'modelClass' => $this->formClass,
                'checkAccess' => [$this, 'checkAccess'],
                'batchFormClass' => AddressPostalCodeBatchForm::class,
                'method' => 'batchUpdate'
            ],
        ]);
    }
}