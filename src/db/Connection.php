<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\workerman\db;

use yii\db\Exception;
use yii\db\Transaction;

/**
 * Class Connection
 * @package lujie\workerman\db
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class Connection extends \yii\db\Connection
{
    /**
     * @param null $isolationLevel
     * @return Transaction|null
     * @throws Exception
     * @inheritdoc
     */
    public function beginTransaction($isolationLevel = null)
    {
        try {
            return parent::beginTransaction($isolationLevel);
        } catch (Exception $e) {
            if (ConnectionHelper::isLostConnection($e, $this)) {
                return parent::beginTransaction($isolationLevel);
            }
            throw $e;
        }
    }
}