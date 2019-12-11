<?php
/**
 * @copyright Copyright (c) 2019
 */

use lujie\extend\constants\StatusConst;

return [
    [
        'user_id' => 1,
        'name' => 'app1',
        'key' => 'app1.key',
        'secret' => 'app1.secret',
        'status' => StatusConst::STATUS_ACTIVE,
    ],
    [
        'user_id' => 1,
        'name' => 'app11',
        'key' => 'app11.key',
        'secret' => 'app11.secret',
        'status' => StatusConst::STATUS_INACTIVE,
    ],
    [
        'user_id' => 2,
        'name' => 'app2',
        'key' => 'app2.key',
        'secret' => 'app2.secret',
        'status' => StatusConst::STATUS_ACTIVE,
    ],
];
