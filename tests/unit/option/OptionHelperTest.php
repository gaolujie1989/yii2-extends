<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\common\models\tests\unit\option;

use lujie\common\item\forms\ItemForm;
use lujie\common\item\models\Item;
use lujie\common\item\models\ItemBarcode;
use lujie\common\option\helpers\OptionHelper;
use lujie\common\option\models\Option;

class OptionHelperTest extends \Codeception\Test\Unit
{
    /**
     * @throws \ImagickException
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \yii\db\IntegrityException
     * @inheritdoc
     */
    public function testMe(): void
    {
        $optionsFile = __DIR__ . '/../fixtures/data/options.php';
        $updateResult = OptionHelper::updateOptions($optionsFile);
        $expected = [
            'created' => 11,
            'updated' => 0,
            'skipped' => 0,
        ];
        static::assertEquals($expected, $updateResult);
        static::assertEquals(11, Option::find()->count());

        $optionsData = require $optionsFile;
        unset(
            $optionsData['optionValueType']['FLOAT'],
            $optionsData['optionTag']['primary'],
            $optionsData['optionTag']['info'],
        );
        $updateResult = OptionHelper::updateOptions($optionsData, true);
        $expected = [
            'created' => 0,
            'updated' => 3,
            'skipped' => 5,
            'deleted' => 3,
        ];
        static::assertEquals($expected, $updateResult);
        static::assertEquals(8, Option::find()->count());
    }
}
