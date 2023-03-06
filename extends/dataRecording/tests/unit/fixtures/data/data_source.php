<?php
/**
 * @copyright Copyright (c) 2019
 */

use lujie\extend\constants\StatusConst;

return [
    [
        'data_source_id' => 1,
        'data_account_id' => 1,
        'name' => 'mock_account1_source1',
        'type' => 'MOCK_TYPE1',
        'status' => StatusConst::STATUS_ACTIVE,
    ],
    [
        'data_source_id' => 2,
        'data_account_id' => 2,
        'name' => 'mock_account2_source2',
        'type' => 'MOCK_TYPE2',
        'status' => StatusConst::STATUS_INACTIVE,
    ],
];
