<?php

namespace lujie\alias\behaviors\tests\unit;

use lujie\alias\behaviors\MoneyAliasBehavior;
use lujie\alias\behaviors\tests\unit\fixtures\TestAliasComponent;

class MoneyAliasBehaviorTest extends \Codeception\Test\Unit
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

    // tests
    public function testMe(): void
    {
        $testAliasComponent = new TestAliasComponent([
            'as unit' => [
                'class' => MoneyAliasBehavior::class,
                'aliasProperties' => [
                    'price' => 'price_cent',
                ],
            ]
        ]);

        $testAliasComponent->price_cent = 123456;
        $this->assertEquals(1234.56, $testAliasComponent->price);
        $testAliasComponent->price = 654.321;
        $this->assertEquals(65432, $testAliasComponent->price_cent);
    }
}
