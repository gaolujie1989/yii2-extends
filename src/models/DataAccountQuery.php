<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\recording\models;

use lujie\common\account\models\AccountQuery;

/**
 * This is the ActiveQuery class for [[Account]].
 *
 * @method DataAccountQuery id($id)
 * @method DataAccountQuery orderById($sort = SORT_ASC)
 * @method DataAccountQuery indexById()
 * @method int getId()
 * @method array getIds()
 *
 * @method DataAccountQuery accountId($accountId)
 * @method DataAccountQuery modelType($modelType)
 * @method DataAccountQuery type($type)
 * @method DataAccountQuery status($status)
 * @method DataAccountQuery name($name)
 * @method DataAccountQuery username($username)
 *
 * @method DataAccountQuery createdAtBetween($from, $to = null)
 * @method DataAccountQuery updatedAtBetween($from, $to = null)
 *
 * @method DataAccountQuery active()
 * @method DataAccountQuery inActive()
 *
 * @method DataAccountQuery orderByAccountId($sort = SORT_ASC)
 * @method DataAccountQuery orderByCreatedAt($sort = SORT_ASC)
 * @method DataAccountQuery orderByUpdatedAt($sort = SORT_ASC)
 *
 * @method DataAccountQuery indexByAccountId()
 *
 * @method array getAccountIds()
 *
 * @method array|DataAccount[] all($db = null)
 * @method array|DataAccount|null one($db = null)
 * @method array|DataAccount[] each($batchSize = 100, $db = null)
 *
 * @see DataAccount
 */
class DataAccountQuery extends AccountQuery
{
}
