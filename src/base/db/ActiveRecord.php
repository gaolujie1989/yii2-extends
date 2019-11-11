<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\stock\base\db;

use lujie\extend\db\IdFieldTrait;
use lujie\extend\db\SaveTrait;
use lujie\extend\db\TraceableBehaviorTrait;
use lujie\extend\db\TransactionTrait;
use Yii;
use yii\base\InvalidConfigException;
use yii\db\Connection;

/**
 * Class ActiveRecord
 *
 * @property int $id
 *
 * @package lujie\inventory\base\db
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ActiveRecord extends \yii\db\ActiveRecord
{
    use TraceableBehaviorTrait, IdFieldTrait, SaveTrait, TransactionTrait;

    /**
     * @return Connection|object
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public static function getDb(): Connection
    {
        $app = Yii::$app;
        $db = Yii::$app->params['stockDB'] ?? null;
        return $db ? $app->get($db) : parent::getDb();
    }
}
