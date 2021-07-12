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

    public $importFormClass = ChargeTableFileImportForm::class;
}
