<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\sales\order\center;


interface SalesChannelInterface
{
    public function shipSalesOrder();

    public function cancelSalesOrder();
}