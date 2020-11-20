<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\fulfillment\models;

use lujie\common\account\models\AccountQuery;

/**
 * This is the ActiveQuery class for [[Account]].
 *
 * @method array|FulfillmentAccount[] all($db = null)
 * @method array|FulfillmentAccount|null one($db = null)
 * @method array|FulfillmentAccount[] each($batchSize = 100, $db = null)
 *
 * @see FulfillmentAccount
 */
class FulfillmentAccountQuery extends AccountQuery
{

}