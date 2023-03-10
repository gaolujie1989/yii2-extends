<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\workerman\db;

use Yii;
use yii\db\Connection;
use yii\db\Exception;

/**
 * Class ConnectionHelper
 * @package lujie\workerman\db
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ConnectionHelper
{
    /**
     * @param Exception $exception
     * @param Connection $connection
     * @param bool $resetConnection
     * @return bool
     * @throws Exception
     */
    public static function isLostConnection(Exception $exception, Connection $connection, bool $resetConnection = true): bool
    {
        $errorCode = $exception->errorInfo[1] ?? 'Unknown';
        if ($errorCode === 2006 || $errorCode === 2013
            || strpos($exception->getMessage(), 'MySQL server has gone away') !== false) {
            if ($resetConnection) {
                $connection->close();
                $connection->open();
            }
            Yii::warning("MYSQL ERROR: {$errorCode}. Connection Lost And Reset...", __METHOD__);
            return true;
        }
        return false;
    }
}
