<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\sales\channel\models;

use lujie\common\account\models\Account;

/**
 * Class SalesChannelAccount
 * @package lujie\sales\channel\models
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class SalesChannelAccount extends Account
{
    public const MODEL_TYPE = 'SALES_CHANNEL';

    /**
     * @return SalesChannelAccountQuery
     * @inheritdoc
     */
    public static function find(): SalesChannelAccountQuery
    {
        return (new SalesChannelAccountQuery(static::class))->modelType(static::MODEL_TYPE);
    }
}
