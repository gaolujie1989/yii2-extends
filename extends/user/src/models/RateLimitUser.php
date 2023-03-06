<?php

namespace lujie\user\models;

use lujie\extend\filters\RateLimitTrait;
use yii\filters\RateLimitInterface;

/**
 * Class RateLimitUser
 * @package lujie\user\models
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class RateLimitUser extends User implements RateLimitInterface
{
    use RateLimitTrait;
}
