<?php
/**
 * @copyright Copyright (c) 2019
 */

use lujie\extend\constants\StatusConst;

return [
    '1' => [
        'data_account_id' => 1,
        'name' => 'mock_account1',
        'type' => 'MOCK',
        'url' => 'url',
        'username' => 'username',
        'password' => 'password',
        'status' => StatusConst::STATUS_ACTIVE,
    ],
    '2' => [
        'data_account_id' => 2,
        'name' => 'mock_account2',
        'type' => 'MOCK',
        'url' => 'url',
        'username' => 'username',
        'password' => 'password',
        'status' => StatusConst::STATUS_INACTIVE,
    ],
];
