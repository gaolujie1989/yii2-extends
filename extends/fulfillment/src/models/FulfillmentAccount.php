<?php

namespace lujie\fulfillment\models;

use lujie\common\account\models\Account;
use lujie\common\account\models\AccountQuery;

/**
 * Class FulfillmentAccount
 * @package lujie\fulfillment\models
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class FulfillmentAccount extends Account
{
    public const MODEL_TYPE = 'FULFILLMENT';

    /**
     * {@inheritdoc}
     * @return AccountQuery|FulfillmentAccountQuery the active query used by this AR class.
     */
    public static function find(): AccountQuery
    {
        return (new FulfillmentAccountQuery(static::class))->modelType(static::MODEL_TYPE);
    }
}
