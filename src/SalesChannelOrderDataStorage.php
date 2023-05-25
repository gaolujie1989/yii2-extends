<?php
/**
 * @copyright Copyright (c) 2023
 */

namespace lujie\sales\channel;

use lujie\data\storage\ActiveRecordDataStorage;
use lujie\sales\channel\models\SalesChannelOrder;
use lujie\sales\channel\models\SalesChannelOrderData;

/**
 * Class SalesChannelOrderDataStorage
 * @package lujie\sales\channel
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class SalesChannelOrderDataStorage extends ActiveRecordDataStorage
{
    public $modelClass = SalesChannelOrderData::class;

    public $key = 'sales_channel_order_id';

    /**
     * @param $key
     * @param $value
     * @return bool
     * @inheritdoc
     */
    public function set($key, $value): bool
    {
        if ($key instanceof SalesChannelOrder) {
            $key = $key->sales_channel_order_id;
        }
        $value = ['order_data' => $value];
        return parent::set($key, $value);
    }
}
