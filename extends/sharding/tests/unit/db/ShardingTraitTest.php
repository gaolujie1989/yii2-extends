<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\sharding\tests\unit\db;

use lujie\extend\helpers\ComponentHelper;
use lujie\sharding\rules\ShardingRangeRule;
use lujie\sharding\tests\unit\fixtures\Migration;
use yii\db\Query;

class ShardingTraitTest extends \Codeception\Test\Unit
{
    public function testMe(): void
    {
        $time2022 = strtotime('2022-01-01');
        $archivedTime = $time2022 - 3600;
        $currentTime = $time2022 + 3600;
        Migration::$tableShardingRules = [
            'apply_time' => [
                'class' => ShardingRangeRule::class,
                'ranges' => [
                    '_archived' => [0, $time2022],
                    '' => [$time2022, strtotime('2029-01-01')],
                ]
            ]
        ];
        Migration::$dbShardingRules = [
            'apply_time' => [
                'class' => ShardingRangeRule::class,
                'ranges' => [
                    'Archived' => [0, $time2022],
                    '' => [$time2022, strtotime('2029-01-01')],
                ]
            ]
        ];
        $migration = new Migration();
        $migration->version = 'test_archived';
        $migration->apply_time = $archivedTime;
        $migration->validate();
        $this->assertEquals('{{%migration_archived}}', Migration::tableName());
        $this->assertEquals('dbArchived', ComponentHelper::getName(Migration::getDb()));
        $this->assertTrue($migration->save(false));
        $one = (new Query())
            ->from('{{%migration_archived}}')
            ->andWhere(['version' => 'test_archived'])
            ->one();
        $this->assertEquals($archivedTime, $one['apply_time']);
        $exists = (new Query())
            ->from('{{%migration}}')
            ->andWhere(['version' => 'test_archived'])
            ->exists();
        $this->assertFalse($exists);

        $migration = new Migration();
        $migration->version = 'test_current';
        $migration->apply_time = $currentTime;
        $migration->validate();
        $this->assertEquals('{{%migration}}', Migration::tableName());
        $this->assertEquals('db', ComponentHelper::getName(Migration::getDb()));
        $this->assertTrue($migration->save(false));
        $one = (new Query())
            ->from('{{%migration}}')
            ->andWhere(['version' => 'test_current'])
            ->one();
        $this->assertEquals($currentTime, $one['apply_time']);
        $exists = (new Query())
            ->from('{{%migration_archived}}')
            ->andWhere(['version' => 'test_current'])
            ->exists();
        $this->assertFalse($exists);

        $count = Migration::updateAll(['version' => 'migration_archived_1'], ['apply_time' => $archivedTime]);
        $this->assertEquals(1, $count);
        $this->assertEquals('{{%migration_archived}}', Migration::tableName());
        $this->assertEquals('dbArchived', ComponentHelper::getName(Migration::getDb()));

        $count = Migration::updateAll(['version' => 'test_current_1'], ['apply_time' => $currentTime]);
        $this->assertEquals(1, $count);
        $this->assertEquals('{{%migration}}', Migration::tableName());
        $this->assertEquals('db', ComponentHelper::getName(Migration::getDb()));

        /** @var Migration $record */
        $record = Migration::find()->andWhere(['apply_time' => $archivedTime])->one();
        $this->assertNotNull($record);
        $this->assertEquals('migration_archived_1', $record->version);

        /** @var Migration $record */
        $record = Migration::find()->andWhere(['apply_time' => $currentTime])->one();
        $this->assertNotNull($record);
        $this->assertEquals('test_current_1', $record->version);
    }
}