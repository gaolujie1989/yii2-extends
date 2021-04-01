<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\helpers;

use Exception;
use Throwable;
use yii\base\InvalidConfigException;
use yii\db\Connection;
use yii\di\Instance;

/**
 * Class TransactionHelper
 * @package lujie\extend\helpers
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class TransactionHelper
{
    /**
     * @param callable $callable
     * @param mixed|string|Connection $db
     * @param mixed|bool $rollBackResult
     * @return mixed
     * @throws InvalidConfigException
     * @throws Throwable
     * @throws \yii\db\Exception
     * @inheritdoc
     */
    public static function transaction(callable $callable, $db, $rollBackResult = false)
    {
        if (is_string($db)) {
            $db = Instance::ensure($db);
        }
        if ($db instanceof Connection) {
            $transaction = $db->beginTransaction();
            if ($transaction === null) {
                return $callable();
            }
            try {
                $result = $callable();
                if ($result === $rollBackResult) {
                    $transaction->rollBack();
                } else {
                    $transaction->commit();
                }
                return $result;
            } catch (Exception $e) {
                $transaction->rollBack();
                throw $e;
            } catch (Throwable $e) {
                $transaction->rollBack();
                throw $e;
            }
        } else {
            return $callable();
        }
    }
}
