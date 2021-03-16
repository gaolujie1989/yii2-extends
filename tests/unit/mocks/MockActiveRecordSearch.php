<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\tests\unit\mocks;

use lujie\extend\db\SearchTrait;

/**
 * Class MockActiveQuery
 * @package lujie\extend\tests\unit\mocks
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class MockActiveRecordSearch extends MockActiveRecord
{
    use SearchTrait;
}
