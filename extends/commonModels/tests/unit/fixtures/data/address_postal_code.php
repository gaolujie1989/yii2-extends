<?php
/**
 * @copyright Copyright (c) 2019
 */

use lujie\extend\constants\StatusConst;

$type = 'ISLAND';
return [
    [
        'type' => $type,
        'country' => 'DE',
        'postal_code' => '18565',
        'status' => StatusConst::STATUS_ACTIVE
    ],
    [
        'type' => $type,
        'country' => 'DE',
        'postal_code' => '18566',
        'status' => StatusConst::STATUS_INACTIVE
    ],
    [
        'type' => $type,
        'country' => 'EE',
        'postal_code' => '88001-88005',
        'status' => StatusConst::STATUS_ACTIVE
    ],
    [
        'type' => $type,
        'country' => 'EE',
        'postal_code' => '88006-88009',
        'status' => StatusConst::STATUS_INACTIVE
    ],
    [
        'type' => $type,
        'country' => 'GB',
        'postal_code' => 'GY*',
        'status' => StatusConst::STATUS_ACTIVE
    ],
    [
        'type' => $type,
        'country' => 'GB',
        'postal_code' => 'PA20-PA29',
        'status' => StatusConst::STATUS_ACTIVE
    ],
];
