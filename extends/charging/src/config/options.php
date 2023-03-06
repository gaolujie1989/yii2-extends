<?php
/**
 * @copyright Copyright (c) 2019
 */

use lujie\charging\models\ChargePrice;

return [
    'chargeStatus' => [
        'ESTIMATE' => ChargePrice::STATUS_ESTIMATE,
        'GENERATED' => ChargePrice::STATUS_GENERATED,
        'CANCELLED' => ChargePrice::STATUS_CANCELLED,
        'FAILED' => ChargePrice::STATUS_FAILED,
    ],
    'chargeType' => [],
];