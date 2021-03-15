<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\tests\unit;

use lujie\extend\tests\unit\mocks\MockActiveRecordForm;
use yii\helpers\VarDumper;

class FormTraitTest extends \Codeception\Test\Unit
{
    /**
     * @inheritdoc
     */
    public function testRules(): void
    {
        MockActiveRecordForm::$columns = [
            'mock_id', 'mock_value',
            'mock_key', 'mock_no', 'mock_name', 'additional',
            'created_by', 'created_at', 'updated_by', 'updated_at'
        ];
        MockActiveRecordForm::$rules = [
            [['mock_key', 'mock_no', 'mock_name'], 'string', 'max' => 50],
            [['mock_id', 'mock_value', 'created_by', 'created_at', 'updated_by', 'updated_at'], 'integer'],
            [['additional'], 'safe']
        ];
        $form = new MockActiveRecordForm();
        $rules = [
            [['mock_key', 'mock_no', 'mock_name'], 'string', 'max' => 50],
            [['mock_id', 'mock_value'], 'integer'],
            [['mock_price', 'mockCopy'], 'safe'],
            [['created_time', 'updated_time'], 'date'],
        ];
        $this->assertEquals($rules, array_values($form->rules()), VarDumper::dumpAsString($form->rules()));
        $form->load([
            'mock_id' => 1,
            'mock_key' => '123',
            'mock_no' => 'xxx_ooo',
            'created_time' => '2021-01-01',
            'mock_price' => 12.3,
        ], '');
        $attributeValues = [
            'mock_id' => 1,
            'mock_key' => '123',
            'mock_no' => 'xxx_ooo',
            'created_time' => '2021-01-01T00:00:00+08:00',
            'mock_price' => 12.3,
            'additional' => [
                'mock_price' => 12.3,
            ]
        ];
        $this->assertEquals($attributeValues, $form->getAttributes(array_keys($attributeValues)));
    }
}
