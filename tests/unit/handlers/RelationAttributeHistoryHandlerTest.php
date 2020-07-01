<?php

namespace lujie\ar\history\behaviors\tests\unit;

use lujie\ar\history\handlers\RelationAttributeHistoryHandler;
use lujie\ar\relation\behaviors\tests\unit\fixtures\models\TestAddress;
use lujie\ar\relation\behaviors\tests\unit\fixtures\models\TestOrderItem;
use yii\helpers\VarDumper;

class RelationAttributeHistoryHandlerTest extends \Codeception\Test\Unit
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
    public function testOne(): void
    {
        $handler = new RelationAttributeHistoryHandler([
            'attributes' => ['street'],
            'multi' => false,
        ]);
        $testAddress1 = new TestAddress([
            'street' => 'street 1'
        ]);
        $testAddress2 = new TestAddress([
            'street' => 'street 2'
        ]);
        $diff = $handler->diff($testAddress1, $testAddress2);
        $excepted = [
            'modified' => [
                'street' => '"street 1" -> "street 2"'
            ]
        ];
        $this->assertEquals($excepted, $diff, VarDumper::dumpAsString($diff));
    }

    /**
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     * @inheritdoc
     */
    public function testMulti(): void
    {
        $handler = new RelationAttributeHistoryHandler([
            'attributes' => ['item_no', 'ordered_qty'],
            'multi' => true,
            'indexAttribute' => 'test_order_item_id',
        ]);
        $orderItem1 = new TestOrderItem([
            'test_order_item_id' => 11,
            'item_no' => 'item1',
            'ordered_qty' => 1,
        ]);
        $orderItem2 = new TestOrderItem([
            'test_order_item_id' => 22,
            'item_no' => 'item2',
            'ordered_qty' => 2,
        ]);
        $orderItem3 = new TestOrderItem([
            'test_order_item_id' => 33,
            'item_no' => 'item3',
            'ordered_qty' => 3,
        ]);
        $orderItem22 = new TestOrderItem([
            'test_order_item_id' => 22,
            'item_no' => 'item22',
            'ordered_qty' => 22,
        ]);
        $diff = $handler->diff([$orderItem1, $orderItem2], [$orderItem22, $orderItem3]);
        $excepted = [
            'added' => [
                33 => [
                    'item_no' => 'item3',
                    'ordered_qty' => 3,
                ]
            ],
            'deleted' => [
                11 => [
                    'item_no' => 'item1',
                    'ordered_qty' => 1,
                ]
            ],
            'modified' => [
                22 => [
                    'item_no' => '"item2" -> "item22"',
                    'ordered_qty' => '"2" -> "22"',
                ]
            ],
        ];
        $this->assertEquals($excepted, $diff, VarDumper::dumpAsString($diff));
    }
}
