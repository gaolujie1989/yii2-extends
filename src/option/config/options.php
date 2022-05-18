<?php
/**
 * @copyright Copyright (c) 2019
 */

use lujie\common\option\models\Option;
use lujie\extend\constants\ExecStatusConst;
use lujie\extend\constants\StatusConst;

return [
    'optionType' => [
        'optionType' => 'Option Type',
        'optionValueType' => 'Option Value Type',
        'optionTag' => 'Option Tag',
    ],
    'optionValueType' => [
        'STRING' => ['name'=>'STRING', 'value' => Option::VALUE_TYPE_STRING, 'tag' => 'info'],
        'INT' => ['name'=>'INTEGER', 'value' => Option::VALUE_TYPE_INT, 'tag' => 'success'],
        'FLOAT' => ['name'=>'FLOAT', 'value' => Option::VALUE_TYPE_FLOAT, 'tag' => 'warning'],
    ],
    'optionTag' => [
        'primary' => ['name' => 'Primary', 'tag' => 'primary' ],
        'info' => ['name' => 'Info', 'tag' => 'info' ],
        'success' => ['name' => 'Success', 'tag' => 'success' ],
        'warning' => ['name' => 'Warning', 'tag' => 'warning' ],
        'danger' => ['name' => 'Danger', 'tag' => 'danger' ],
    ],
    'commonStatus' => [
        'ACTIVE' => ['name' => 'Active', 'value' => StatusConst::STATUS_ACTIVE, 'tag' => 'success'],
        'INACTIVE' => ['name' => 'Inactive', 'value' => StatusConst::STATUS_INACTIVE, 'tag' => 'info'],
    ],
    'execStatus' => [
        'PENDING' => ['name' => 'Pending', 'value' => ExecStatusConst::EXEC_STATUS_PENDING, 'tag' => 'info'],
        'RUNNING' => ['name' => 'Running', 'value' => ExecStatusConst::EXEC_STATUS_RUNNING, 'tag' => 'primary'],
        'SUCCESS' => ['name' => 'Success', 'value' => ExecStatusConst::EXEC_STATUS_SUCCESS, 'tag' => 'success'],
        'FAILED' => ['name' => 'Failed', 'value' => ExecStatusConst::EXEC_STATUS_FAILED, 'tag' => 'danger'],
        'SKIPPED' => ['name' => 'Skipped', 'value' => ExecStatusConst::EXEC_STATUS_SKIPPED, 'tag' => 'info'],
        'QUEUED' => ['name' => 'Queued', 'value' => ExecStatusConst::EXEC_STATUS_QUEUED, 'tag' => 'warning'],
    ],
];