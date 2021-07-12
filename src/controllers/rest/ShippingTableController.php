<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\charging\controllers\rest;

use lujie\charging\forms\ShippingTableBatchForm;
use lujie\charging\forms\ShippingTableFileImportForm;
use lujie\charging\models\ShippingTable;
use lujie\charging\ShippingTableFileExporter;
use lujie\extend\rest\ActiveController;
use Yii;

/**
 * Class ShippingTableController
 * @package lujie\charging\controllers\rest
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ShippingTableController extends ActiveController
{
    public $modelClass = ShippingTable::class;

    public $importFormClass = ShippingTableFileImportForm::class;
}
