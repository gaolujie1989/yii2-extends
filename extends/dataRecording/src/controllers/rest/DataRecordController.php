<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\recording\controllers\rest;

use lujie\data\recording\models\DataRecord;
use lujie\extend\rest\ActiveController;

/**
 * Class DataRecordController
 * @package kiwi\data\recording\controllers\backend
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class DataRecordController extends ActiveController
{
    /**
     * @var string|DataRecord
     */
    public $modelClass = DataRecord::class;
}
