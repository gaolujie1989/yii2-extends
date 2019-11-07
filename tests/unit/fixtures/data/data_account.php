<?php
/**
 * @copyright Copyright (c) 2019
 */

use lujie\extend\constants\StatusConst;

return [
    [
        'data_account_id' => 1,
        'name' => 'mock_account1',
        'type' => 'MOCK',
        'url' => 'url',
        'username' => 'username',
        'password' => 'password',
        'status' => StatusConst::STATUS_ACTIVE,
    ],
    [
        'data_account_id' => 2,
        'name' => 'mock_account2',
        'type' => 'MOCK',
        'url' => 'url',
        'username' => 'username',
        'password' => 'password',
        'status' => StatusConst::STATUS_INACTIVE,
    ],
];
