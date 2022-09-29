<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\db;

/**
 * Class ActiveRecord
 * @package lujie\extend\db
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class Migration extends \yii\db\Migration
{
    use DropTableTrait, TraceableColumnTrait;

    public $tableName;
    public $traceCreate = true;
    public $traceUpdate = true;
    public $traceBy = true;
}