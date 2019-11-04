<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\test\unit\db;


use lujie\extend\helpers\ClassHelper;
use lujie\extend\tests\unit\fixtures\forms\MigrationForm;
use lujie\extend\tests\unit\fixtures\models\Migration;
use lujie\extend\tests\unit\fixtures\searches\MigrationSearch;
use lujie\extend\tests\unit\mocks\MockActiveRecord;
use lujie\extend\tests\unit\mocks\MockIdentity;
use yii\base\InvalidArgumentException;

class ClassHelperTest extends \Codeception\Test\Unit
{
    /**
     * @var \lujie\extend\tests\UnitTester
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
        $this->assertNull(ClassHelper::getFormClass(MockActiveRecord::class));
        $this->assertNull(ClassHelper::getSearchClass(MockActiveRecord::class));

        $this->assertEquals(MigrationForm::class, ClassHelper::getFormClass(Migration::class));
        $this->assertEquals(MigrationSearch::class, ClassHelper::getSearchClass(Migration::class));

        $this->assertEquals('Migration', ClassHelper::getClassShortName(Migration::class));
        $this->assertEquals('Migration', ClassHelper::getClassShortName(new Migration()));

        $this->assertEquals(Migration::class, ClassHelper::getBaseRecordClass(MigrationForm::class));
        $this->assertEquals(Migration::class, ClassHelper::getBaseRecordClass(new MigrationSearch()));

        $this->expectException(InvalidArgumentException::class);
        ClassHelper::getBaseRecordClass(new MockIdentity());
    }
}
