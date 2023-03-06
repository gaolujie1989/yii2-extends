<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\tests\unit\mocks;

use lujie\extend\authclient\BaseCookieClient;

/**
 * Class MockCookieClient
 * @package lujie\extend\tests\unit\mocks
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class MockCookieClient extends BaseCookieClient
{
    public $httpClientOptions = [
        'transport' => MockTransport::class
    ];

    protected function initUserAttributes()
    {
    }
}
