<?php

namespace lujie\alias\behaviors\tests\unit;

use lujie\alias\behaviors\tests\unit\fixtures\TestAliasComponent;
use lujie\alias\behaviors\TimestampAliasBehavior;

class TimestampAliasBehaviorTest extends \Codeception\Test\Unit
{
    protected function _before()
    {
    }

    protected function _after()
    {
    }

    // tests
    public function testMe(): void
    {
        $testAliasComponent = new TestAliasComponent([
            'as timestamp' => [
                'class' => TimestampAliasBehavior::class,
                'timezone' => 'Asia/Tokyo',
                'aliasProperties' => [
                    'created_time' => 'created_at'
                ]
            ]
        ]);
        $datetime = '2019-01-01 08:00:00';
        $jpDateTime = '2019-01-01T09:00:00+09:00';
        $testAliasComponent->created_at = strtotime($datetime);
        $this->assertEquals($jpDateTime, $testAliasComponent->created_time);

        $datetime = '2019-01-02 08:00:00';
        $jpDateTime = '2019-01-02T09:00:00+09:00';
        $testAliasComponent->created_time = $jpDateTime;
        $this->assertEquals(strtotime($datetime), $testAliasComponent->created_at);

        $testAliasComponent = new TestAliasComponent([
            'as timestamp' => [
                'class' => TimestampAliasBehavior::class,
                'timezone' => 'Asia/Tokyo',
                'format' => 'Y-m-d H:i:s',
                'aliasProperties' => [
                    'updated_at' => 'updated_time'
                ]
            ]
        ]);
        $datetime = '2019-01-01 08:00:00';
        $jpDateTime = '2019-01-01 09:00:00';
        $testAliasComponent->updated_time = $jpDateTime;
        $this->assertEquals(strtotime($datetime), $testAliasComponent->updated_at);

        $datetime = '2019-01-02 08:00:00';
        $jpDateTime = '2019-01-02 09:00:00';
        $testAliasComponent->updated_at = strtotime($datetime);
        $this->assertEquals($jpDateTime, $testAliasComponent->updated_time);
    }
}
