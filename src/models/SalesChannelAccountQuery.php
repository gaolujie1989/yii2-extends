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
 * @method SalesChannelAccountQuery indexById()
 * @method int getId()
 * @method array getIds()
 *
 * @method SalesChannelAccountQuery accountId($accountId)
 * @method SalesChannelAccountQuery modelType($modelType)
 * @method SalesChannelAccountQuery type($type)
 * @method SalesChannelAccountQuery status($status)
 * @method SalesChannelAccountQuery name($name)
 * @method SalesChannelAccountQuery username($username)
 *
 * @method SalesChannelAccountQuery createdAtBetween($from, $to = null)
 * @method SalesChannelAccountQuery updatedAtBetween($from, $to = null)
 *
 * @method SalesChannelAccountQuery active()
 * @method SalesChannelAccountQuery inActive()
 *
 * @method SalesChannelAccountQuery orderByAccountId($sort = SORT_ASC)
 * @method SalesChannelAccountQuery orderByCreatedAt($sort = SORT_ASC)
 * @method SalesChannelAccountQuery orderByUpdatedAt($sort = SORT_ASC)
 *
 * @method SalesChannelAccountQuery indexByAccountId()
 *
 * @method array getAccountIds()
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
