<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\fulfillment\models;

use lujie\common\account\models\Account;
use lujie\common\account\models\AccountQuery;

/**
 * This is the ActiveQuery class for [[Account]].
 *
 * @method FulfillmentAccountQuery id($id)
 * @method FulfillmentAccountQuery orderById($sort = SORT_ASC)
 * @method int getId()
 * @method array getIds()
 *
 * @method FulfillmentAccountQuery accountId($accountId)
 * @method FulfillmentAccountQuery modelType($modelType)
 * @method FulfillmentAccountQuery type($type)
 * @method FulfillmentAccountQuery status($status)
 * @method FulfillmentAccountQuery name($name)
 *
 * @method FulfillmentAccountQuery active()
 * @method FulfillmentAccountQuery inActive()
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