<?php

namespace lujie\upload\tests\unit;

use creocoder\flysystem\Filesystem;
use lujie\batch\BatchForm;
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

class BatchFormTest extends \Codeception\Test\Unit
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
     * @param ModelEvent $event
     * @inheritdoc
     */
    public function setInvalidBeforeDelete(ModelEvent $event): void
    {
        $event->isValid = false;
    }

    /**
     * @throws \Throwable
     * @inheritdoc
     */
    public function testBatchUpdate(): void
    {
        $condition = ['version' => ['m000000_000000_base', 'm140506_102106_rbac_init']];
        $batchForm = new MigrationBatchForm([
            'modelClass' => Migration::class,
            'condition' => $condition,
        ]);
        $batchForm->setAttributes(['apply_time' => 'abc']);
        $message = 'apply_time should be int, not validated';
        $this->assertFalse($batchForm->batchUpdate(), $message);
        $this->assertTrue($batchForm->hasErrors('apply_time'), $message);

        $batchForm->validateModels = true;
        $batchForm->setAttributes(['apply_time' => -1]);
        $message = 'apply_time should >= 0, not validated';
        $this->assertFalse($batchForm->batchUpdate(), $message);
        $this->assertTrue($batchForm->hasErrors('apply_time'), $message);

        $batchForm->setAttributes(['apply_time' => 123]);
        $this->assertTrue($batchForm->batchUpdate());
        $migrations = Migration::find()->andWhere($condition)->asArray()->all();
        $expected = [
            [
                'version' => 'm000000_000000_base',
                'apply_time' => 123
            ],
            [
                'version' => 'm140506_102106_rbac_init',
                'apply_time' => 123
            ]
        ];
        $this->assertEquals($expected, $migrations);

        Event::on(Migration::class, Migration::EVENT_BEFORE_UPDATE, [$this, 'setInvalidBeforeUpdate']);
        $batchForm->setAttributes(['apply_time' => 321]);
        $message = 'model will not update with no error';
        $this->assertFalse($batchForm->batchUpdate(), $message);
        $this->assertFalse($batchForm->hasErrors(), $message);
        $migrations = Migration::find()->andWhere($condition)->asArray()->all();
        $this->assertEquals($expected, $migrations);
    }

    /**
     * @throws \Throwable
     * @throws \yii\web\NotFoundHttpException
     * @inheritdoc
     */
    public function testBatchDelete(): void
    {
        $condition = ['version' => ['m000000_000000_base', 'm140506_102106_rbac_init']];
        $batchFrom = new MigrationBatchForm([
            'modelClass' => Migration::class,
            'condition' => $condition,
        ]);

        Event::on(Migration::class, Migration::EVENT_BEFORE_DELETE, [$this, 'setInvalidBeforeDelete']);
        $message = 'model will not delete with no error';
        $this->assertFalse($batchFrom->batchDelete(), $message);
        $count = Migration::find()->andWhere($condition)->count();
        $this->assertEquals(2, $count);

        Event::off(Migration::class, Migration::EVENT_BEFORE_DELETE, [$this, 'setInvalidBeforeDelete']);
        $this->assertTrue($batchFrom->batchDelete());
        $count = Migration::find()->andWhere($condition)->count();
        $this->assertEquals(0, $count);
    }
}
