<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\stock\base\db;

use lujie\extend\db\DbConnectionTrait;

/**
 * Class ActiveRecord
 * @package lujie\stock\base\db
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ActiveRecord extends \lujie\extend\db\ActiveRecord
{
    use DbConnectionTrait;
}
