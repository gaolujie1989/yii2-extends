<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\scheduling\tests\unit;

use lujie\executing\tests\unit\mocks\TestExecutable;
use lujie\scheduling\CronTask;
use lujie\scheduling\tests\unit\mocks\InvalidTestTask;
use lujie\scheduling\tests\unit\mocks\TestTask;
use Yii;
use yii\base\InvalidConfigException;

class CronTaskTest extends \Codeception\Test\Unit
{


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

    /**
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function testMe(): void
    {
        $data = [
            'id' => 'testCronTask1',
            'expression' => '* * * * *',
        ];
        $cronTask = new CronTask($data);

        $this->assertEquals($data['id'], $cronTask->getId());
        $this->assertEquals($data['expression'], $cronTask->expression);
        $this->assertTrue($cronTask->isDue());
        $dateTime = \DateTime::createFromFormat('Y-m-d H:i', date('Y-m-d H:i', strtotime('+1 min')));
        $this->assertEquals($dateTime, $cronTask->getNextRunTime());
        $this->assertEquals(Yii::$app->timeZone, $cronTask->getTimezone());
        $this->assertFalse($cronTask->shouldLocked());
        $this->assertNull($cronTask->getMutex());
        $this->assertEquals(0, $cronTask->getTimeout());
        $this->assertFalse($cronTask->shouldQueued());
        $this->assertNull($cronTask->getQueue());
        $this->assertEquals(0, $cronTask->getTtr());
        $this->assertEquals(0, $cronTask->getAttempts());

        $data = [
            'id' => 'testCronTask2',
            'expression' => '*/2 * * * *',
            'timezone' => 'Asia/Tokyo',
            'shouldLocked' => true,
            'mutex' => 'mutex',
            'timeout' => 5,
            'shouldQueued' => true,
            'queue' => 'queue',
            'ttr' => 30,
            'attempts' => 3,
        ];
        $cronTask = new CronTask($data);
        $this->assertEquals($data['id'], $cronTask->getId());
        $this->assertEquals($data['expression'], $cronTask->expression);
        $isDue = !(date('i') % 2);
        $this->assertEquals($isDue, $cronTask->isDue());
        $dateTime = \DateTime::createFromFormat(
            'Y-m-d H:i',
            date('Y-m-d H:i', strtotime($isDue ? '+2 min' : '+1 min'))
        );
        $dateTime->setTimezone(new \DateTimeZone($data['timezone']));
        $this->assertEquals($dateTime, $cronTask->getNextRunTime());
        $this->assertEquals($data['timezone'], $cronTask->getTimezone());
        $this->assertEquals($data['shouldLocked'], $cronTask->shouldLocked());
        $this->assertEquals(Yii::$app->get($data['mutex']), $cronTask->getMutex());
        $this->assertEquals($data['timeout'], $cronTask->getTimeout());
        $this->assertEquals($data['shouldQueued'], $cronTask->shouldQueued());
        $this->assertEquals(Yii::$app->get($data['queue']), $cronTask->getQueue());
        $this->assertEquals($data['ttr'], $cronTask->getTtr());
        $this->assertEquals($data['attempts'], $cronTask->getAttempts());
    }

    /**
     * @throws InvalidConfigException
     * @throws \yii\base\NotSupportedException
     * @inheritdoc
     */
    public function testExecute(): void
    {
        $cronTask = new CronTask([
            'id' => 'testCronTask1',
            'expression' => '* * * * *',
        ]);

        $this->assertFalse($cronTask->execute());

        $cronTask->executable = 'abc';
        $this->assertFalse($cronTask->execute());

        $cronTask->executable = ['abc' => 'abc'];
        $this->assertFalse($cronTask->execute());

        $cronTask->executable = [
            'class' => TestExecutable::class,
        ];
        Yii::$app->params['xxx'] = null;
        $cronTask->execute();
        $this->assertEquals('xxx', Yii::$app->params['xxx']);

        $cronTask->executable = [$this, 'forTestTask'];
        Yii::$app->params['xxx'] = null;
        $cronTask->execute();
        $this->assertEquals('xxx', Yii::$app->params['xxx']);

        $cronTask->executable = static function () {
            Yii::$app->params['xxx'] = 'xxx';
        };
        Yii::$app->params['xxx'] = null;
        $cronTask->execute();
        $this->assertEquals('xxx', Yii::$app->params['xxx']);
    }
}
