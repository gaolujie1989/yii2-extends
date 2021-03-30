<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\sales\channel\models;

use lujie\common\account\models\AccountQuery;

/**
 * This is the ActiveQuery class for [[Account]].
 *
 * @method SalesChannelAccountQuery id($id)
 * @method SalesChannelAccountQuery orderById($sort = SORT_ASC)
 * @method int getId()
 * @method array getIds()
 *
 * @method SalesChannelAccountQuery accountId($accountId)
 * @method SalesChannelAccountQuery modelType($modelType)
 * @method SalesChannelAccountQuery type($type)
 * @method SalesChannelAccountQuery status($status)
 * @method SalesChannelAccountQuery name($name)
 *
 * @method SalesChannelAccountQuery active()
 * @method SalesChannelAccountQuery inActive()
 *
 * @method array|SalesChannelAccount[] all($db = null)
 * @method array|SalesChannelAccount|null one($db = null)
 * @method array|SalesChannelAccount[] each($batchSize = 100, $db = null)
 *
 * @see SalesChannelAccount
 */
class SalesChannelAccountQuery extends AccountQuery
{
}
