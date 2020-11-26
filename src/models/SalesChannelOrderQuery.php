<?php

namespace lujie\sales\channel\models;

use lujie\db\fieldQuery\behaviors\FieldQueryBehavior;

/**
 * This is the ActiveQuery class for [[SalesChannelOrder]].
 *
 * @method SalesChannelOrderQuery id($id)
 * @method SalesChannelOrderQuery orderById($sort = SORT_ASC)
 * @method int getId()
 * @method array getIds()
 *
 * @method SalesChannelOrderQuery salesChannelOrderId($salesChannelOrderId)
 * @method SalesChannelOrderQuery salesChannelAccountId($salesChannelAccountId)
 * @method SalesChannelOrderQuery salesChannelStatus($salesChannelStatus)
 * @method SalesChannelOrderQuery orderId($orderId)
 * @method SalesChannelOrderQuery orderStatus($orderStatus)
 * @method SalesChannelOrderQuery externalOrderKey($externalOrderKey)
 * @method SalesChannelOrderQuery externalOrderStatus($externalOrderStatus)
 * @method SalesChannelOrderQuery orderPushedStatus($orderPushedStatus)
 *
 * @method array|SalesChannelOrder[] all($db = null)
 * @method array|SalesChannelOrder|null one($db = null)
 * @method array|SalesChannelOrder[] each($batchSize = 100, $db = null)
 *
 * @see SalesChannelOrder
 */
class SalesChannelOrderQuery extends \yii\db\ActiveQuery
{

    /**
     * @return array
     * @inheritdoc
     */
    public function behaviors(): array
    {
        return [
            'fieldQuery' => [
                'class' => FieldQueryBehavior::class,
                'queryFields' => [
                    'salesChannelOrderId' => 'sales_channel_order_id',
                    'salesChannelAccountId' => 'sales_channel_account_id',
                    'salesChannelStatus' => 'sales_channel_status',
                    'orderId' => 'order_id',
                    'orderStatus' => 'order_status',
                    'externalOrderKey' => 'external_order_key',
                    'externalOrderStatus' => 'external_order_status',
                    'orderPushedStatus' => 'order_pushed_status',
                ]
            ]
        ];
    }

}
