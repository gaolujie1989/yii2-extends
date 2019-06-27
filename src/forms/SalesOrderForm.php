<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\sales\order\center\forms;


use lujie\sales\order\center\models\SalesOrder;

/**
 * Class SalesOrderSearch
 * @package lujie\sales\order\center\searches
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class SalesOrderForm extends SalesOrder
{
    /**
     * @return array
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            [['sales_account_id', 'sales_account_name', 'customer_id',
                'platform', 'country', 'shipping_country', 'currency'], 'required'],
            [['sales_account_id', 'customer_id', 'shipping_address_id', 'billing_address_id', 'external_order_id',
                'payment_status', 'shipping_status',
                'ordered_at', 'paid_at', 'shipped_at', 'completed_at', 'closed_at', 'refund_at', 'cancelled_at',
                'status'], 'integer'],
            [['shipping_numbers'], 'safe'],
            [['sales_account_name', 'customer_phone', 'external_order_no',
                'payment_method', 'transaction_no', 'shipping_method'], 'string', 'max' => 50],
            [['customer_email'], 'string', 'max' => 100],
            [['platform'], 'string', 'max' => 20],
            [['country', 'shipping_country'], 'string', 'max' => 2],
            [['currency'], 'string', 'max' => 3],
            [['cancel_reason', 'note'], 'string', 'max' => 255],
        ];
    }
}
