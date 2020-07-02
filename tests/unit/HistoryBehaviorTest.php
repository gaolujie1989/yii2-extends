<?php

namespace lujie\ar\history\behaviors\tests\unit;

use lujie\ar\history\behaviors\HistoryBehavior;
use lujie\ar\history\handlers\MapAttributeHistoryHandler;
use lujie\ar\history\handlers\RelationAttributeHistoryHandler;
use lujie\ar\history\models\ModelHistory;
use lujie\ar\relation\behaviors\RelationSavableBehavior;
use lujie\ar\relation\behaviors\tests\unit\fixtures\models\TestOrder;
use lujie\data\loader\ArrayDataLoader;
use yii\helpers\VarDumper;

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

    public function getTestOrder()
    {
        $testOrder = new TestOrder();
        $testOrder->attachBehavior('relationSave', [
            'class' => RelationSavableBehavior::class,
            'relations' => ['shippingAddress', 'orderItems'],
            'indexKeys' => ['orderItems' => 'item_no'],
        ]);
        $testOrder->attachBehavior('history', [
            'class' => HistoryBehavior::class,
            'attributes' => [
                'order_no',
                'customer_email',
                'status',
                'shippingAddress.street',
                'shippingAddress',
                'orderItems',
            ],
            'attributeHandlers' => [
                'status' => [
                    'class' => MapAttributeHistoryHandler::class,
                    'labelLoader' => [
                        'class' => ArrayDataLoader::class,
                        'data' => [
                            '0' => 'Pending',
                            '1' => 'Processing',
                            '10' => 'Shipped',
                        ],
                    ]
                ],
                'shippingAddress' => [
                    'class' => RelationAttributeHistoryHandler::class,
                    'attributes' => ['street'],
                ],
                'orderItems' => [
                    'class' => RelationAttributeHistoryHandler::class,
                    'attributes' => ['item_no', 'ordered_qty'],
                    'multi' => true,
                    'indexAttribute' => 'item_no',
                ],
            ]
        ]);
        return $testOrder;
    }

    /**
     * @inheritdoc
     */
    public function testMe(): void
    {
        $testOrder = $this->getTestOrder();
        //create test order
        $testOrder->order_no = 'OrderNo-1';
        $testOrder->customer_email = 'x1@xxx.com';
        $testOrder->status = 0;
        $testOrder->shippingAddress = ['street' => 'street1'];
        $testOrder->orderItems = [
            [
                'item_no' => 'Item1',
                'ordered_qty' => 1
            ],
            [
                'item_no' => 'Item2',
                'ordered_qty' => 2
            ],
        ];
        $this->assertTrue($testOrder->save(false));

        //update test order
        $testOrder->order_no = 'OrderNo-1-1';
        $testOrder->customer_email = 'x1@xxx.com';
        $testOrder->status = 1;
        $testOrder->shippingAddress = ['street' => 'street1-1'];
        $testOrder->orderItems = [
            [
                'item_no' => 'Item2',
                'ordered_qty' => 1
            ],
            [
                'item_no' => 'Item3',
                'ordered_qty' => 1
            ],
        ];
        $this->assertTrue($testOrder->save(false));

        $historyQuery = ModelHistory::find()->modelType('TestOrder')->modelId($testOrder->test_order_id);
        $this->assertEquals(1, $historyQuery->count());
        $history = $historyQuery->one();
        $excepted = [
            'order_no' => [
                'attribute' => 'order_no',
                'oldValue' => 'OrderNo-1',
                'newValue' => 'OrderNo-1-1',
                'diffValue' => ['modified' => '"OrderNo-1" -> "OrderNo-1-1"'],
            ],
            'status' => [
                'attribute' => 'status',
                'oldValue' => 0,
                'newValue' => 1,
                'diffValue' => ['modified' => '"Pending" -> "Processing"'],
            ],
            'customer_email' => [
                'attribute' => 'customer_email',
                'newValue' => 'x1@xxx.com',
                'oldValue' => 'x1@xxx.com',
                'diffValue' => null,
            ],
            'shippingAddress.street' => [
                'attribute' => 'shippingAddress.street',
                'oldValue' => 'street1',
                'newValue' => 'street1-1',
                'diffValue' => [
                    'modified' => '"street1" -> "street1-1"'
                ],
            ],
            'shippingAddress' => [
                'attribute' => 'shippingAddress',
                'oldValue' => ['street' => 'street1'],
                'newValue' => ['street' => 'street1-1'],
                'diffValue' => [
                    'modified' => [
                        'street' => '"street1" -> "street1-1"'
                    ]
                ],
            ],
            'orderItems' => [
                'attribute' => 'orderItems',
                'oldValue' => [
                    [
                        'item_no' => 'Item1',
                        'ordered_qty' => 1
                    ],
                    [
                        'item_no' => 'Item2',
                        'ordered_qty' => 2
                    ],
                ],
                'newValue' => [
                    'Item2' => [
                        'item_no' => 'Item2',
                        'ordered_qty' => 1
                    ],
                    'Item3' => [
                        'item_no' => 'Item3',
                        'ordered_qty' => 1
                    ],
                ],
                'diffValue' => [
                    'added' => [
                        'Item3' => [
                            'item_no' => 'Item3',
                            'ordered_qty' => 1,
                        ]
                    ],
                    'deleted' => [
                        'Item1' => [
                            'item_no' => 'Item1',
                            'ordered_qty' => 1,
                        ]
                    ],
                    'modified' => [
                        'Item2' => [
                            'ordered_qty' => '"2" -> "1"'
                        ]
                    ]
                ],
            ],
        ];
        $this->assertEquals($excepted, $history->details, VarDumper::dumpAsString($history->details));
    }
}
