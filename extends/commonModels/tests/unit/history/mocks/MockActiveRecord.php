<?php
/**
 * @copyright Copyright (c) 2023
 */

namespace lujie\common\tests\unit\history\mocks;

use lujie\common\history\models\ModelHistoryRelationTrait;

class MockActiveRecord extends \lujie\extend\tests\unit\mocks\MockActiveRecord
{
    use ModelHistoryRelationTrait;
}
