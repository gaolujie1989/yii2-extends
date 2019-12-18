<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\recording\tests\unit\mocks;

use Iterator;
use lujie\data\loader\BaseDataLoader;
use lujie\data\loader\DataLoaderInterface;
use lujie\extend\authclient\RestOAuth2Client;
use yii\base\BaseObject;

/**
 * Class MockApiClient
 * @package lujie\data\recording\tests\unit\mocks
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class MockClientLoader extends BaseDataLoader
{
    public function get($key)
    {
        return ['class' => MockApiClient::class];
    }
}
