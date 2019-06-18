<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\scheduling\tests\unit;


use lujie\scheduling\CronTask;
use lujie\scheduling\tests\unit\mocks\InvalidTestTask;
use lujie\scheduling\tests\unit\mocks\TestTask;
use Yii;
use yii\base\InvalidConfigException;

class CronTaskTest extends \Codeception\Test\Unit
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

    public function forTestTask(): void
    {
        Yii::$app->params['xxx'] = 'xxx';
    }

    public function testMe()
    {
        $data = [
            'taskCode' => 'testCronTask1',
            'expression' => '* * * * *',
        ];
        $cronTask = new CronTask(['data' => $data]);

        $this->assertEquals($data['taskCode'], $cronTask->getTaskCode());
        $this->assertEquals($data['expression'], $cronTask->getExpression());
        $this->assertEquals('', $cronTask->getTaskDescription());
        $this->assertTrue($cronTask->isDue());
        $dateTime = \DateTime::createFromFormat('Y-m-d H:i', date('Y-m-d H:i', strtotime('+1 min')));
        $this->assertEquals($dateTime, $cronTask->getNextRunTime());
        $this->assertEquals(Yii::$app->timeZone, $cronTask->getTimezone());
        $this->assertFalse($cronTask->isWithoutOverlapping());
        $this->assertNull($cronTask->getMutex());
        $this->assertEquals(0, $cronTask->getExpiresAt());
        $this->assertFalse($cronTask->shouldQueued());
        $this->assertNull($cronTask->getQueue());
        $this->assertEquals(0, $cronTask->getTtr());
        $this->assertEquals(0, $cronTask->getAttempts());

        $data = [
            'taskCode' => 'testCronTask2',
            'expression' => '*/2 * * * *',
            'taskDescription' => 'taskDescription2',
            'timezone' => 'Asia/Tokyo',
            'isWithoutOverlapping' => true,
            'mutex' => 'mutex',
            'expiresAt' => 5,
            'shouldQueued' => true,
            'queue' => 'queue',
            'ttr' => 30,
            'attempts' => 3,
        ];
        $cronTask = new CronTask(['data' => $data]);
        $this->assertEquals($data['taskCode'], $cronTask->getTaskCode());
        $this->assertEquals($data['expression'], $cronTask->getExpression());
        $this->assertEquals($data['taskDescription'], $cronTask->getTaskDescription());
        $isDue = !(date('i') % 2);
        $this->assertEquals($isDue, $cronTask->isDue());
        $dateTime = \DateTime::createFromFormat(
            'Y-m-d H:i',
            date('Y-m-d H:i', strtotime($isDue ? '+2 min' : '+1 min'))
        );
        $dateTime->setTimezone(new \DateTimeZone($data['timezone']));
        $this->assertEquals($dateTime, $cronTask->getNextRunTime());
        $this->assertEquals($data['timezone'], $cronTask->getTimezone());
        $this->assertEquals($data['isWithoutOverlapping'], $cronTask->isWithoutOverlapping());
        $this->assertEquals(Yii::$app->get($data['mutex']), $cronTask->getMutex());
        $this->assertEquals($data['expiresAt'], $cronTask->getExpiresAt());
        $this->assertEquals($data['shouldQueued'], $cronTask->shouldQueued());
        $this->assertEquals(Yii::$app->get($data['queue']), $cronTask->getQueue());
        $this->assertEquals($data['ttr'], $cronTask->getTtr());
        $this->assertEquals($data['attempts'], $cronTask->getAttempts());
    }

    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function testExecute(): void
    {
        $cronTask = new CronTask(['data' => [
            'taskCode' => 'testCronTask1',
            'expression' => '* * * * *',
        ]]);

        $this->assertFalse($cronTask->execute());

        $cronTask->data['callback'] = 'abc';
        $this->assertFalse($cronTask->execute());

        $cronTask->data['callback'] = ['abc' => 'abc'];
        $this->assertFalse($cronTask->execute());

        $cronTask->data['callback'] = [
            'class' => InvalidTestTask::class,
        ];
        try {
            $cronTask->execute();
            $this->assertTrue(false, 'Should throw exception');
        } catch (\Exception $e) {
            $this->assertInstanceOf(InvalidConfigException::class, $e);
        }

        $cronTask->data['callback'] = [
            'class' => TestTask::class,
        ];
        Yii::$app->params['xxx'] = null;
        $cronTask->execute();
        $this->assertEquals('xxx', Yii::$app->params['xxx']);

        $cronTask->data['callback'] = [$this, 'forTestTask'];
        Yii::$app->params['xxx'] = null;
        $cronTask->execute();
        $this->assertEquals('xxx', Yii::$app->params['xxx']);

        $cronTask->data['callback'] = static function () {
            Yii::$app->params['xxx'] = 'xxx';
        };
        Yii::$app->params['xxx'] = null;
        $cronTask->execute();
        $this->assertEquals('xxx', Yii::$app->params['xxx']);
    }
}
