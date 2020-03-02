<?php

namespace lujie\ar\snapshoot\behaviors\tests\unit;

use lujie\ar\snapshoot\behaviors\ConditionalSnapshootBehavior;
use lujie\ar\snapshoot\behaviors\SnapshootBehavior;
use lujie\ar\snapshoot\behaviors\tests\unit\fixtures\models\TestItem;
use lujie\ar\snapshoot\behaviors\tests\unit\fixtures\models\TestItemSnapshoot;

class ConditionalSnapshootBehaviorTest extends \Codeception\Test\Unit
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
        $testItem = new TestItem();
        $testItem->attachBehavior('snapshoot', [
            'class' => SnapshootBehavior::class
        ]);
        $testItem->attachBehavior('conditionalSnapshoot', [
            'class' => ConditionalSnapshootBehavior::class,
            'attribute' => 'status',
            'snapshootOn' => [1],
        ]);

        $testItem->setAttributes([
            'item_no' => 'ITEM_A',
            'item_name' => 'ITEM_AAA',
            'status' => 0
        ]);
        $this->assertTrue($testItem->save(false));

        $this->assertEquals(0, $testItem->snapshoot_id);
        $query = TestItemSnapshoot::find()->andWhere(['test_item_id' => $testItem->test_item_id]);
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
        /** @var TestItemSnapshoot $snapshoot */
        $snapshoot = $query->orderBy(['test_item_snapshoot_id' => SORT_DESC])->one();
        $this->assertEquals($testItem->getAttributes(null, ['snapshoot_id']), $snapshoot->getAttributes(null, ['test_item_snapshoot_id']));
        $this->assertEquals($testItem->snapshoot_id, $snapshoot->test_item_snapshoot_id);
    }
}
