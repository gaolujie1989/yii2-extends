<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\charging\controllers\rest;

use lujie\charging\forms\ShippingZoneFileImportForm;
use lujie\charging\models\ShippingZone;
use lujie\extend\rest\ActiveController;
use Yii;

/**
 * Class ShippingZoneController
 * @package lujie\charging\controllers\rest
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ShippingZoneController extends ActiveController
{
    public $modelClass = ShippingZone::class;


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
