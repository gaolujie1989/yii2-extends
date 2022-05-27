<?php
/**
 * @copyright Copyright (c) 2019
 */

use lujie\charging\models\ChargePrice;

return [
    'chargeType' => [
        ChargePrice::STATUS_ESTIMATE => 'ESTIMATE',
        ChargePrice::STATUS_GENERATED => 'GENERATED',
        ChargePrice::STATUS_CANCELLED => 'CANCELLED',
        ChargePrice::STATUS_FAILED => 'FAILED',
    ],
];