<?php

namespace lujie\upload\tests\unit;

use creocoder\flysystem\Filesystem;
use lujie\batch\BatchForm;
use lujie\batch\MultipleForm;
use lujie\batch\tests\unit\fixtures\Migration;
use lujie\batch\tests\unit\mocks\MigrationBatchForm;
use lujie\upload\behaviors\FileBehavior;
use lujie\upload\behaviors\UploadBehavior;
use lujie\upload\forms\UploadForm;
use lujie\upload\forms\UploadModelFileForm;
use Yii;
use yii\base\Event;
use yii\base\ModelEvent;
use yii\di\Instance;
use yii\helpers\FileHelper;
use yii\helpers\VarDumper;

class MultipleFormTest extends \Codeception\Test\Unit
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
     * @param ModelEvent $event
     * @inheritdoc
     */
    public function setInvalidBeforeUpdate(ModelEvent $event): void
    {
        $event->isValid = false;
    }

    /**
     * @throws \Throwable
     * @inheritdoc
     */
    public function testSave(): void
    {
        $data = [
            [
                'version' => 'm000000_000000_mmm123',
                'apply_time' => 123
            ],
            [
                'version' => 'm000000_000000_mmm456',
                'apply_time' => 456
            ],
            [
                'version' => 'm000000_000000_mmm789',
                'apply_time' => 789
            ],
        ];
        $condition = ['version' => ['m000000_000000_mmm123', 'm000000_000000_mmm456', 'm000000_000000_mmm789']];
        $multipleForm = new MultipleForm([
            'modelClass' => Migration::class,
        ]);
        $multipleForm->load($data, '');
        $this->assertTrue($multipleForm->save());
        $migrations = Migration::find()->andWhere($condition)->asArray()->all();
        $this->assertEquals($data, $migrations);

        $data = [
            [
                'version' => 'm000000_000000_mmm123',
                'apply_time' => 1231
            ],
            [
                'version' => 'm000000_000000_mmm456',
                'apply_time' => 4561
            ],
            [
                'version' => 'm000000_000000_mmm789',
                'apply_time' => 7891
            ],
        ];
        $multipleForm->load($data, '');
        $this->assertTrue($multipleForm->save());
        $migrations = Migration::find()->andWhere($condition)->asArray()->all();
        $this->assertEquals($data, $migrations);

        $data = [
            [
                'version' => 'm000000_000000_mmm123',
                'apply_time' => 123
            ],
            [
                'version' => 'm000000_000000_mmm456',
                'apply_time' => 456
            ],
            [
                'version' => 'm000000_000000_mmm789',
                'apply_time' => -789
            ],
        ];
        $multipleForm->load($data, '');
        $this->assertFalse($multipleForm->save());
        $this->assertTrue($multipleForm->hasErrors());

        Event::on(Migration::class, Migration::EVENT_BEFORE_UPDATE, [$this, 'setInvalidBeforeUpdate']);
        $multipleForm->load($data, '');
        $multipleForm->clearErrors();
        $this->assertFalse($multipleForm->save(false));
        $this->assertFalse($multipleForm->hasErrors());
    }
}
