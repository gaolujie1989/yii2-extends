<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\tests\unit\mocks;

use lujie\extend\filters\RateLimitTrait;
use yii\filters\RateLimitInterface;

/**
 * Class MockRateLimitIdentity
 * @package lujie\extend\tests\unit\mocks
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class MockRateLimitIdentity extends MockIdentity implements RateLimitInterface
{
    use RateLimitTrait;
}
