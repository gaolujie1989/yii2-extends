<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\db;

use Yii;
use yii\base\InvalidConfigException;
use yii\db\Connection;

/**
 * Trait IdFieldsTrait
 * @package lujie\extend\db
 */
trait DbConnectionTrait
{
    /**
     * @return Connection
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public static function getDb(): Connection
    {
        $app = Yii::$app;
        $db = $app->params['modelDBs'][static::class] ?? $app->params['modelDBs'][self::class] ?? 'db';
        /** @var Connection $connection */
        $connection = $app->get($db);
        return $connection;
    }
}
