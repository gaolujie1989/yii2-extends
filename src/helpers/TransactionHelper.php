<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\helpers;

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
     * @param string|object|Connection|\yii\mongodb\Connection $db
     * @return bool
     * @throws InvalidConfigException
     * @throws \Throwable
     * @inheritdoc
     */
    public static function transaction(callable $callable, $db): bool
    {
        if (is_string($db)) {
            $db = Instance::ensure($db);
        }
        if ($db instanceof Connection) {
            $transaction = $db->beginTransaction();
            try {
                $result = $callable();
                if ($result === false) {
                    $transaction->rollBack();
                } else {
                    $transaction->commit();
                }
                return $result;
            } catch (\Exception $e) {
                $transaction->rollBack();
                throw $e;
            } catch (\Throwable $e) {
                $transaction->rollBack();
                throw $e;
            }
        } else {
            return $callable();
        }
    }
}
