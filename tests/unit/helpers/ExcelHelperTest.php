<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\test\unit\db;

use lujie\extend\helpers\ExcelHelper;
use Yii;

class ExcelHelperTest extends \Codeception\Test\Unit
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
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @inheritdoc
     */
    public function testMe(): void
    {
        $file = Yii::getAlias('@runtime/testExcel.xlsx');
        if (file_exists($file)) {
            unlink($file);
        }
        $excelData = [
            [
                'AAA' => 'A111',
                'BBB' => 'B111',
            ],
            [
                'AAA' => 'A222',
                'BBB' => 'B222',
            ],
        ];
        ExcelHelper::writeExcel($file, $excelData, true, false);
        $this->assertFileExists($file);
        $this->assertEquals($excelData, ExcelHelper::readExcel($file, true, false));

        $file = Yii::getAlias('@runtime/testExcel.xlsx');
        $excelData = [
            'AB' => [
                ['A111', 'B111'],
                ['A222', 'B222'],
            ],
            'CD' => [
                ['C111', 'D111'],
                ['C222', 'D222'],
            ]
        ];
        ExcelHelper::writeExcel($file, $excelData, false, true);
        $this->assertFileExists($file);
        $this->assertEquals($excelData, ExcelHelper::readExcel($file, false, true));
    }
}
