<?php
/**
 * @copyright Copyright (c) 2019
 */

use lujie\common\option\models\Option;
use lujie\extend\constants\ExecStatusConst;
use lujie\extend\constants\StatusConst;

return [
    Option::TYPE_OPTION_TYPE => [
        'OPTION_TYPE' => Option::TYPE_OPTION_TYPE,
        'OPTION_VALUE_TYPE' => Option::TYPE_OPTION_VALUE_TYPE,
        'OPTION_TAG' => Option::TYPE_OPTION_TAG,
    ],
    Option::TYPE_OPTION_VALUE_TYPE => [
        'STRING' => ['value' => Option::VALUE_TYPE_STRING, 'tag' => 'info'],
        'INTEGER' => ['value' => Option::VALUE_TYPE_INT, 'tag' => 'success'],
        'FLOAT' => ['value' => Option::VALUE_TYPE_FLOAT, 'tag' => 'warning'],
    ],
    Option::TYPE_OPTION_TAG => [
        'Primary' => ['value' => 'primary', 'tag' => 'primary' ],
        'Info' => ['value' => 'info', 'tag' => 'info' ],
        'Success' => ['value' => 'success', 'tag' => 'success' ],
        'Warning' => ['value' => 'warning', 'tag' => 'warning' ],
        'Danger' => ['value' => 'danger', 'tag' => 'danger' ],
    ],
    'commonStatus' => [
        'ACTIVE' => ['value' => StatusConst::STATUS_ACTIVE, 'tag' => 'success'],
        'INACTIVE' => ['value' => StatusConst::STATUS_INACTIVE, 'tag' => 'info'],
    ],
    'execStatus' => [
        'PENDING' => ['value' => ExecStatusConst::EXEC_STATUS_PENDING, 'tag' => 'info'],
        'RUNNING' => ['value' => ExecStatusConst::EXEC_STATUS_RUNNING, 'tag' => 'primary'],
        'SUCCESS' => ['value' => ExecStatusConst::EXEC_STATUS_SUCCESS, 'tag' => 'success'],
        'FAILED' => ['value' => ExecStatusConst::EXEC_STATUS_FAILED, 'tag' => 'danger'],
        'SKIPPED' => ['value' => ExecStatusConst::EXEC_STATUS_SKIPPED, 'tag' => 'info'],
        'QUEUED' => ['value' => ExecStatusConst::EXEC_STATUS_QUEUED, 'tag' => 'warning'],
    ],
    'owner' => [
        'DEFAULT' => 0,
    ]
];