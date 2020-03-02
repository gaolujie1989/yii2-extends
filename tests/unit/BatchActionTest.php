<?php

namespace lujie\upload\tests\unit;

use lujie\batch\BatchAction;
use lujie\batch\tests\unit\fixtures\Migration;
use lujie\batch\tests\unit\mocks\MigrationBatchForm;
use Yii;
use yii\base\Event;
use yii\base\ModelEvent;
use yii\web\Request;
use yii\web\ServerErrorHttpException;

class BatchActionTest extends \Codeception\Test\Unit
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
     * @throws \yii\web\NotFoundHttpException
     * @inheritdoc
     */
    public function testBatchUpdate(): void
    {
        Yii::$app->set('request', new Request());
        $batchAction = new BatchAction('batch', null, [
            'modelClass' => Migration::class,
            'batchFormClass' => MigrationBatchForm::class
        ]);

        Yii::$app->getRequest()->setBodyParams(['apply_time' => 'abc']);
        $condition = ['version' => ['m000000_000000_base', 'm140506_102106_rbac_init']];
        $batchForm = $batchAction->run($condition['version']);
        $this->assertTrue($batchForm->hasErrors('apply_time'));

        Yii::$app->getRequest()->setBodyParams(['apply_time' => '123']);
        $batchForm = $batchAction->run($condition['version']);
        $this->assertFalse($batchForm->hasErrors());
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
        Yii::$app->getRequest()->setBodyParams(['apply_time' => '321']);
        try {
            $batchForm = $batchAction->run($condition['version']);
            $this->assertTrue(false, 'should throw exception');
        } catch (\Exception $e) {
            $this->assertInstanceOf(ServerErrorHttpException::class, $e);
        }
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
        Yii::$app->set('request', new Request());
        $batchAction = new BatchAction('batch', null, [
            'modelClass' => Migration::class,
            'batchFormClass' => MigrationBatchForm::class,
            'method' => 'batchDelete'
        ]);
        $condition = ['version' => ['m000000_000000_base', 'm140506_102106_rbac_init']];

        Event::on(Migration::class, Migration::EVENT_BEFORE_DELETE, [$this, 'setInvalidBeforeDelete']);
        try {
            $batchForm = $batchAction->run($condition['version']);
            $this->assertTrue(false, 'should throw exception');
        } catch (\Exception $e) {
            $this->assertInstanceOf(ServerErrorHttpException::class, $e);
        }
        $count = Migration::find()->andWhere($condition)->count();
        $this->assertEquals(2, $count);

        Event::off(Migration::class, Migration::EVENT_BEFORE_DELETE, [$this, 'setInvalidBeforeDelete']);
        $batchForm = $batchAction->run($condition['version']);
        $count = Migration::find()->andWhere($condition)->count();
        $this->assertEquals(0, $count);
    }
}
