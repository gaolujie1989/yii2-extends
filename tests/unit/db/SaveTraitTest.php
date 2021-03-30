<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\test\unit\db;

use lujie\extend\tests\unit\mocks\MockActiveRecord;
use yii\base\ModelEvent;
use yii\db\BaseActiveRecord;
use yii\db\Exception;

class SaveTraitTest extends \Codeception\Test\Unit
{
    protected function _before()
    {
    }

    protected function _after()
    {
    }

    /**
     * @throws \yii\db\Exception
     * @inheritdoc
     */
    public function testMe(): void
    {
        $mockActiveRecord = new MockActiveRecord([
            'mock_id' => 1,
            'mock_value' => 'abc',
        ]);
        $mockActiveRecord->setIsNewRecord(false);
        $mockActiveRecord->setAttribute('mock_value', 'bcd');
        $this->assertTrue($mockActiveRecord->save(false, ['mock_value']));

        [$attributes] = end(MockActiveRecord::$updates);
        $expected = [
            'mock_value' => 'bcd',
            'updated_at' => time(),
            'updated_by' => '0',
        ];
        $this->assertEquals($expected, $attributes);

        $mockActiveRecord->on(BaseActiveRecord::EVENT_BEFORE_UPDATE, static function (ModelEvent $event) {
            $event->sender->addError('model_value', 'xxx error');
            $event->isValid = false;
        });

        $this->expectException(Exception::class);
        $mockActiveRecord->mustSave();
    }
}
