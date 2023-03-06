<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\sharding\tests\unit\fixtures;

use lujie\sharding\db\ShardingActiveRecordTrait;
use yii\db\ActiveRecord;
use yii\db\Connection;

/**
 * Class Migration
 *
 * @property string $version
 * @property int $apply_time
 *
 * @package tests\unit\fixtures
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class Migration extends ActiveRecord
{
    use ShardingActiveRecordTrait;

    /**
     * @return string
     * @inheritdoc
     */
    public static function tableName(): string
    {
        return static::getShardingTableName('{{%migration}}');
    }

    /**
     * @return Connection
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public static function getDb(): Connection
    {
        return static::getShardingDb('db');
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            [['version'], 'string'],
            [['apply_time'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     * @return MigrationQuery the active query used by this AR class.
     */
    public static function find(): MigrationQuery
    {
        return new MigrationQuery(static::class);
    }
}
