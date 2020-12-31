<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\charging\controllers\rest;

use lujie\batch\BatchAction;
use lujie\charging\ChargeTableFileExporter;
use lujie\charging\ChargeTableFileImporter;
use lujie\charging\forms\ChargeTableBatchForm;
use lujie\charging\forms\ChargeTableFileImportForm;
use lujie\charging\models\ChargeTable;
use lujie\data\exchange\actions\FileExportAction;
use lujie\data\exchange\actions\FileImportAction;
use lujie\extend\rest\ActiveController;
use lujie\upload\actions\UploadAction;
use lujie\upload\forms\UploadForm;
use Yii;

/**
 * Class ChargeTableController
 * @package lujie\charging\controllers\rest
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ChargeTableController extends ActiveController
{
    public $modelClass = ChargeTable::class;

    public $uploadPath = '@uploads/temp';

    public function actions(): array
    {
        return array_merge(parent::actions(), [
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
                    'class' => ChargeTableFileImportForm::class,
                    'fileImporter' => ChargeTableFileImporter::class,
                    'path' => $this->uploadPath
                ]
            ],
            'export' => [
                'class' => FileExportAction::class,
                'modelClass' => $this->searchClass,
                'queryPreparer' => [
                    'asArray' => false,
                ],
                'fileExporter' => ChargeTableFileExporter::class,
                'exportFileName' => 'ChargeTable.xlsx'
            ],
            'batch-update' => [
                'class' => BatchAction::class,
                'modelClass' => $this->formClass,
                'checkAccess' => [$this, 'checkAccess'],
                'batchFormClass' => ChargeTableBatchForm::class,
                'method' => 'batchUpdate'
            ],
            'batch-delete' => [
                'class' => BatchAction::class,
                'modelClass' => $this->formClass,
                'checkAccess' => [$this, 'checkAccess'],
                'batchFormClass' => ChargeTableBatchForm::class,
                'method' => 'batchDelete'
            ],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function actionDownloadTemplate(): void
    {
        $file = '@lujie/charging/templates/ChargeTableTemplate.xlsx';
        $fileName = 'ChargeTableTemplate.xlsx';
        Yii::$app->getResponse()->sendFile(Yii::getAlias($file), $fileName);
    }
}