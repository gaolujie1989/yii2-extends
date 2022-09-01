<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\sharding\tests\unit\fixtures;

use lujie\sharding\db\ShardingActiveQueryTrait;
use yii\db\ActiveQuery;

/**
 * Class MigrationQuery
 * @package lujie\sharding\tests\unit\fixtures
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class MigrationQuery extends ActiveQuery
{
    use ShardingActiveQueryTrait;
}
