<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\fulfillment\base\db;

use lujie\fulfillment\models\FulfillmentAccountRelationTrait;

/**
 * Class ActiveRecord
 * @package lujie\fulfillment\base\db
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ActiveRecord extends \lujie\extend\db\ActiveRecord
{
    use FulfillmentAccountRelationTrait;
}
