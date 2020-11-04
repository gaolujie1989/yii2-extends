<?php
/**
 * @copyright Copyright (c) 2019
 */

$startedAt = strtotime('-7 days');
$endedAt = strtotime('+7 days');
$type = 'ISLAND';
$closure = static function ($value) use ($startedAt, $endedAt, $type) {
    return array_merge($value, [
        'started_at' => $startedAt,
        'ended_at' => $endedAt,
        'type' => $type,
    ]);
};
return array_map($closure, [
    [
        'country' => 'DE',
        'postal_code' => '18565',
    ],
    [
        'country' => 'EE',
        'postal_code' => '88001-88005',
    ],
    [
        'country' => 'GB',
        'postal_code' => 'GY*',
    ],
    [
        'country' => 'GB',
        'postal_code' => 'PA20-PA29',
    ],
]);