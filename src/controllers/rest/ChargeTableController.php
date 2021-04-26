<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\charging\controllers\rest;

use lujie\charging\ChargeTableFileExporter;
use lujie\charging\forms\ChargeTableBatchForm;
use lujie\charging\forms\ChargeTableFileImportForm;
use lujie\charging\models\ChargeTable;
use lujie\extend\rest\ActiveController;
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

    public $importFormClass = ChargeTableFileImportForm::class;

    public $exporterClass = ChargeTableFileExporter::class;

    public $batchFormClass = ChargeTableBatchForm::class;

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
