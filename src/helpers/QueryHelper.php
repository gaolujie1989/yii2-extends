<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\helpers;

use Yii;
use yii\db\Query;

/**
 * Class QueryHelper
 * @package lujie\extend\helpers
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class QueryHelper
{
    /**
     * @param Query $query
     * @return string
     * @inheritdoc
     */
    public static function buildSql(Query $query): string
    {
        [$sql, $params] = Yii::$app->getDb()->getQueryBuilder()->build($query);
        return strtr($sql, array_merge($params, ['{{%' => '', '{{' => '', '}}' => '', '[[' => '', ']]' => '']));
    }
}
