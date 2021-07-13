<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\db;

use Yii;
use yii\base\InvalidConfigException;
use yii\db\BaseActiveRecord;
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
        $modelDBs = $app->params['modelDBs'] ?? [];
        $db = 'db';
        $class = static::class;
        while ($class) {
            if (isset($modelDBs[$class])) {
                $db = $modelDBs[$class];
                break;
            }
            if (!($class instanceof BaseActiveRecord)) {
                break;
            }
            $class = get_parent_class($class);
        }
        return $app->get($db);
    }
}
