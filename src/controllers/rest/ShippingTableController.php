<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\charging\controllers\rest;

use lujie\batch\BatchAction;
use lujie\charging\forms\ShippingTableBatchForm;
use lujie\charging\forms\ShippingTableFileImportForm;
use lujie\charging\forms\ShippingTableForm;
use lujie\charging\models\ShippingTable;
use lujie\charging\ShippingTableFileExporter;
use lujie\charging\ShippingTableFileImporter;
use lujie\data\exchange\actions\FileExportAction;
use lujie\data\exchange\actions\FileImportAction;
use lujie\extend\rest\ActiveController;
use lujie\upload\actions\UploadAction;
use lujie\upload\forms\UploadForm;

/**
 * Class ShippingTableController
 * @package lujie\charging\controllers\rest
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ShippingTableController extends ActiveController
{
    public $modelClass = ShippingTable::class;

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
                    'class' => ShippingTableFileImportForm::class,
                    'fileImporter' => ShippingTableFileImporter::class,
                    'path' => $this->uploadPath
                ]
            ],
            'export' => [
                'class' => FileExportAction::class,
                'modelClass' => $this->searchClass,
                'queryPreparer' => [
                    'asArray' => true,
                ],
                'fileExporter' => ShippingTableFileExporter::class,
                'exportFileName' => 'ShippingTable.xlsx'
            ],
            'batch-update' => [
                'class' => BatchAction::class,
                'modelClass' => ShippingTableForm::class,
                'checkAccess' => [$this, 'checkAccess'],
                'batchFormClass' => ShippingTableBatchForm::class,
                'method' => 'batchUpdate'
            ],
            'batch-delete' => [
                'class' => BatchAction::class,
                'modelClass' => ShippingTableForm::class,
                'checkAccess' => [$this, 'checkAccess'],
                'batchFormClass' => ShippingTableBatchForm::class,
                'method' => 'batchDelete'
            ],
        ]);
    }
}