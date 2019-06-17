<?php

namespace lujie\relation\behaviors\tests\unit;

use lujie\ar\history\behaviors\HistoryBehavior;
use lujie\ar\history\models\History;

class HistoryBehaviorTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    protected function _before()
    {

    }

    protected function _after()
    {
    }

    /**
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     * @inheritdoc
     */
    public function testMe(): void
    {
        $history = new History();
        $history->attachBehavior('history', [
            'class' => HistoryBehavior::class,
        ]);

        $history->setAttributes([
            'table_name' => 'xxx_table',
            'row_id' => 123,
        ]);
        $this->assertTrue($history->save(false));
        /** @var History $insertHistory */
        $query = History::find()->tableName(History::tableName())->rowId($history->id);
        $this->assertEquals(1, $query->count(), 'Should exist one history after insert');
        $insertHistory = $query->one();
        $changedAttributes = ['id', 'table_name', 'row_id', 'created_at', 'created_by'];
        $this->assertEquals(array_fill_keys($changedAttributes, null), $insertHistory->old_data);
        $this->assertEquals($history->getAttributes($changedAttributes), $insertHistory->new_data);

        $changedAttributes = ['table_name', 'row_id', 'old_data', 'new_data'];
        $history->setAttributes([
            'table_name' => 'xxx_table2',
            'row_id' => 321,
            'old_data' => ['old' => '123'],
            'new_data' => ['new' => '123'],
        ]);
        $this->assertTrue($history->save(false));

        $this->assertEquals(2, $query->count());
        /** @var History $updateHistory */
        $updateHistory = $query->orderBy(['id' => SORT_DESC])->one();
        $this->assertEquals([
            'table_name' => 'xxx_table',
            'row_id' => 123,
            'old_data' => null,
            'new_data' => null,
        ], $updateHistory->old_data);
        $this->assertEquals($history->getAttributes($changedAttributes), $updateHistory->new_data);
    }
}
