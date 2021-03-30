<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\common\models\tests\unit\item;

use lujie\common\item\forms\ItemForm;
use lujie\common\item\models\Item;
use lujie\common\item\models\ItemBarcode;
use yii\helpers\VarDumper;

class ItemFormTest extends \Codeception\Test\Unit
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
     * @inheritdoc
     */
    public function testMe(): void
    {
        $itemForm = new ItemForm();
        $itemForm->load([
            'item_no' => 'TEST-1',
            'weight_kg' => '1.1',
            'length_cm' => '86',
            'width_cm' => '64',
            'height_cm' => '42',
            'note' => 'TEST',
            'ean' => '4758468123521',
            'ownSKU' => 'OWN_123'
        ], '');
        $this->assertTrue($itemForm->save(false));
        $item = Item::find()->itemNo('TEST-1')->one();
        $expected = [
            'item_no' => 'TEST-1',
            'weight_g' => '1100',
            'length_mm' => '860',
            'width_mm' => '640',
            'height_mm' => '420',
            'note' => 'TEST',
        ];
        $this->assertEquals($expected, $item->getAttributes(array_keys($expected)));
        $eanQuery = ItemBarcode::find()->codeText('4758468123521');
        $ownSKUQuery = ItemBarcode::find()->codeText('OWN_123');
        $this->assertTrue($eanQuery->exists());
        $this->assertTrue($ownSKUQuery->exists());

        //test barcode validate
        $itemForm = new ItemForm();
        $itemForm->load([
            'item_no' => 'TEST-2',
            'weight_kg' => '2.2',
            'length_cm' => '82',
            'width_cm' => '62',
            'height_cm' => '42',
            'note' => 'TEST',
            'ean' => '4758468123521'
        ], '');
        $this->assertFalse($itemForm->save());
        $this->assertTrue($itemForm->hasErrors('ean'));

        //test barcode update
        $itemForm = ItemForm::findOne($item->item_id);
        $itemForm->ean = null;
        $this->assertTrue($itemForm->save());
        $this->assertTrue($eanQuery->exists());
        $this->assertTrue($ownSKUQuery->exists());

        $itemForm->ean = '';
        $this->assertTrue($itemForm->save());
        $this->assertFalse($eanQuery->exists());
        $this->assertTrue($ownSKUQuery->exists());
    }
}
