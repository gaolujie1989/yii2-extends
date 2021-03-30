<?php

namespace lujie\data\exchange\tests\unit\transformers;

use lujie\data\exchange\transformers\GroupTransformer;

class GroupTransformerTest extends \Codeception\Test\Unit
{


    protected function _before()
    {
    }

    protected function _after()
    {
    }

    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function testMe(): void
    {
        $data = [
            [
                'orderNo' => 'AAA1-BBB1', 'orderDate' => '2019-05-21',
                'country' => 'DE', 'street' => 'DE DDD road',
                'itemNo' => 'XXX1', 'qty' => 1
            ],
            [
                'orderNo' => 'AAA1-BBB1', 'orderDate' => '2019-05-21',
                'country' => 'DE', 'street' => 'DE DDD road',
                'itemNo' => 'XXX3', 'qty' => 3
            ],
            [
                'orderNo' => 'AAA2-BBB2', 'orderDate' => '2019-05-22',
                'country' => 'FR', 'street' => 'FR FFF road',
                'itemNo' => 'XXX2', 'qty' => 2
            ],
        ];
        $expectedData = [
            'AAA1-BBB1' => [
                'orderNo' => 'AAA1-BBB1', 'orderDate' => '2019-05-21',
                'address' => [
                    'country' => 'DE', 'street' => 'DE DDD road',
                ],
                'orderItems' => [
                    'XXX1' => ['itemNo' => 'XXX1', 'qty' => 1],
                    'XXX3' => ['itemNo' => 'XXX3', 'qty' => 3]
                ]
            ],
            'AAA2-BBB2' => [
                'orderNo' => 'AAA2-BBB2', 'orderDate' => '2019-05-22',
                'address' => [
                    'country' => 'FR', 'street' => 'FR FFF road',
                ],
                'orderItems' => [
                    'XXX2' => ['itemNo' => 'XXX2', 'qty' => 2]
                ]
            ],
        ];
        $transformer = new GroupTransformer([
            'groupConfig' => [
                'valueKeys' => [
                    'orderNo', 'orderDate',
                ],
                'multi' => true,
                'indexKey' => 'orderNo',
                'subGroups' => [
                    'orderItems' => [
                        'valueKeys' => [
                            'itemNo', 'qty',
                        ],
                        'multi' => true,
                        'indexKey' => 'itemNo',
                    ],
                    'address' => [
                        'valueKeys' => [
                            'country', 'street',
                        ],
                        'multi' => false,
                    ]
                ],
            ],
        ]);
        $transformedData = $transformer->transform($data);
        $this->assertEquals($expectedData, $transformedData);
    }
}
