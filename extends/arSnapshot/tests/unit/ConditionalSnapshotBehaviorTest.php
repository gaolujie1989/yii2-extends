<?php

namespace lujie\ar\snapshot\behaviors\tests\unit;

use lujie\ar\snapshot\behaviors\ConditionalSnapshotBehavior;
use lujie\ar\snapshot\behaviors\SnapshotBehavior;
use lujie\ar\snapshot\behaviors\tests\unit\fixtures\models\TestItem;
use lujie\ar\snapshot\behaviors\tests\unit\fixtures\models\TestItemSnapshot;

class ConditionalSnapshotBehaviorTest extends \Codeception\Test\Unit
{
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
        $testItem = new TestItem();
        $testItem->attachBehavior('snapshot', [
            'class' => SnapshotBehavior::class
        ]);
        $testItem->attachBehavior('conditionalSnapshot', [
            'class' => ConditionalSnapshotBehavior::class,
            'attribute' => 'status',
            'snapshotOn' => [1],
        ]);

        $testItem->setAttributes([
            'item_no' => 'ITEM_A',
            'item_name' => 'ITEM_AAA',
            'status' => 0
        ]);
        $this->assertTrue($testItem->save(false));

        $this->assertEquals(0, $testItem->snapshot_id);
        $query = TestItemSnapshot::find()->andWhere(['test_item_id' => $testItem->test_item_id]);
        $this->assertEquals(0, $query->count());

        //sleep for updated_at timestamp check will be return true
        sleep(1);
        $testItem->setAttributes([
            'item_no' => 'ITEM_B',
            'item_name' => 'ITEM_BBB',
            'status' => 1
        ]);
        $this->assertTrue($testItem->save(false));

        $this->assertEquals(1, $query->count());
        /** @var TestItemSnapshot $snapshot */
        $snapshot = $query->orderBy(['test_item_snapshot_id' => SORT_DESC])->one();
        $this->assertEquals($testItem->getAttributes(null, ['snapshot_id']), $snapshot->getAttributes(null, ['test_item_snapshot_id']));
        $this->assertEquals($testItem->snapshot_id, $snapshot->test_item_snapshot_id);
    }
}
