<?php

namespace lujie\data\recording\models;

use lujie\common\account\models\Account;
use lujie\common\account\models\AccountQuery;

/**
 * Class DataAccount
 * @package lujie\data\recording\models
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class DataAccount extends Account
{
    public const MODEL_TYPE = 'DATA';

    /**
     * {@inheritdoc}
     * @return AccountQuery|DataAccountQuery the active query used by this AR class.
     */
    public static function find(): AccountQuery
    {
        return (new DataAccountQuery(static::class))->modelType(static::MODEL_TYPE);
    }
}
