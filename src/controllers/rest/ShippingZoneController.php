<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\charging\controllers\rest;

use lujie\batch\BatchAction;
use lujie\charging\forms\ShippingTableBatchForm;
use lujie\charging\forms\ShippingTableFileImportForm;
use lujie\charging\forms\ShippingZoneFileImportForm;
use lujie\charging\models\ShippingTable;
use lujie\charging\models\ShippingZone;
use lujie\charging\ShippingTableFileExporter;
use lujie\charging\ShippingTableFileImporter;
use lujie\data\exchange\actions\FileExportAction;
use lujie\data\exchange\actions\FileImportAction;
use lujie\extend\rest\ActiveController;
use lujie\upload\actions\UploadAction;
use lujie\upload\forms\UploadForm;
use Yii;

/**
 * Class ShippingZoneController
 * @package lujie\charging\controllers\rest
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ShippingZoneController extends ActiveController
{
    public $modelClass = ShippingZone::class;

    public $uploadPath = '@uploads/temp';

    public $importFormClass = ShippingZoneFileImportForm::class;

    /**
     * @inheritdoc
     */
    public function actionDownloadTemplate(): void
    {
        $file = '@lujie/charging/templates/ShippingZoneTemplate.xlsx';
        $fileName = 'ShippingZoneTemplate.xlsx';
        Yii::$app->getResponse()->sendFile(Yii::getAlias($file), $fileName);
    }
}
