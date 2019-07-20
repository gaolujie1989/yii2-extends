<?php

namespace lujie\upload\tests\unit;

use lujie\batch\MultipleAction;
use lujie\batch\tests\unit\fixtures\Migration;
use Yii;
use yii\base\Event;
use yii\base\ModelEvent;
use yii\web\Request;
use yii\web\ServerErrorHttpException;

class MultipleActionTest extends \Codeception\Test\Unit
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
     * @throws \yii\web\NotFoundHttpException
     * @inheritdoc
     */
    public function testBatchUpdate(): void
    {
        Yii::$app->set('request', new Request());
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
        $multipleAction = new MultipleAction('batch', null, [
            'modelClass' => Migration::class,
        ]);

        Yii::$app->getRequest()->setBodyParams($data);
        $multipleForm = $multipleAction->run();
        $this->assertFalse($multipleForm->hasErrors());
        $migrations = Migration::find()->andWhere($condition)->asArray()->all();
        $this->assertEquals($data, $migrations);

        Event::on(Migration::class, Migration::EVENT_BEFORE_UPDATE, [$this, 'setInvalidBeforeUpdate']);
        $data2 = [
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
        Yii::$app->getRequest()->setBodyParams($data2);
        try {
            $multipleForm = $multipleAction->run($condition['version']);
            $this->assertTrue(false, 'should throw exception');
        } catch (\Exception $e) {
            $this->assertInstanceOf(ServerErrorHttpException::class, $e);
        }
        $migrations = Migration::find()->andWhere($condition)->asArray()->all();
        $this->assertEquals($data, $migrations);
    }
}
