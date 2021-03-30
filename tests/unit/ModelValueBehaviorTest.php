<?php

namespace lujie\eav\tests\unit;

use lujie\ar\relation\behaviors\tests\unit\fixtures\models\TestOrder;
use lujie\eav\behaviors\ModelValueBehavior;
use lujie\eav\models\ModelInt;
use lujie\eav\models\ModelString;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;

class ModelValueBehaviorTest extends \Codeception\Test\Unit
{


    protected function _before()
    {
    }

    protected function _after()
    {
    }

    public function testMe(): void
    {
        $testOrder = new TestOrder([
            'order_no' => 'xxx_xxx',
            'customer_email' => 'xxx@xxx.com',
            'shipping_address_id' => 0,
            'order_amount' => 0,
            'paid_amount' => 0,
            'status' => 0,
        ]);
        $behaviors = [
            'modelTexts' => [
                'class' => ModelValueBehavior::class,
                'keys' => ['title', 'name'],
                'channels' => ['EN', 'DE'],
                'valueName' => 'Texts',
                'relationKey' => 'modelTexts',
                'modelClass' => ModelString::class,
            ],
            'modelNumbers' => [
                'class' => ModelValueBehavior::class,
                'keys' => ['price', 'qty'],
                'channels' => ['Amazon', 'Ebay'],
                'valueName' => 'Numbers',
                'relationKey' => 'modelNumbers',
                'modelClass' => ModelInt::class,
            ],
        ];
        $testOrder->attachBehaviors($behaviors);

        $texts = [
            'EN' => [
                'title' => 'title_en',
                'name' => 'name_en',
            ],
            'DE' => [
                'title' => 'title_de',
                'name' => 'name_de',
            ]
        ];
        $testOrder->texts = $texts;
        $numbers = [
            'Amazon' => [
                'price' => '1',
                'qty' => '2',
            ],
            'Ebay' => [
                'price' => '3',
                'qty' => '4',
            ]
        ];
        $testOrder->numbers = $numbers;

        $this->assertTrue($testOrder->save(false));

        $modelTexts = ModelString::find()
            ->modelType('TestOrder')
            ->modelId($testOrder->test_order_id)
            ->select(['key', 'value', 'channel'])
            ->asArray()
            ->all();
        $modelNumbers = ModelInt::find()
            ->modelType('TestOrder')
            ->modelId($testOrder->test_order_id)
            ->select(['key', 'value', 'channel'])
            ->asArray()
            ->all();
        $modelTexts = ArrayHelper::map($modelTexts, 'key', 'value', 'channel');
        $modelNumbers = ArrayHelper::map($modelNumbers, 'key', 'value', 'channel');

        $findTestOrder = TestOrder::findOne($testOrder->test_order_id);
        $findTestOrder->attachBehaviors($behaviors);

        $this->assertEquals($texts, $findTestOrder->texts, VarDumper::dumpAsString($findTestOrder->texts));
        $this->assertEquals($numbers, $findTestOrder->numbers, VarDumper::dumpAsString($findTestOrder->numbers));

        $this->assertEquals($texts, $modelTexts, VarDumper::dumpAsString($modelTexts));
        $this->assertEquals($numbers, $modelNumbers, VarDumper::dumpAsString($modelNumbers));
    }
}
