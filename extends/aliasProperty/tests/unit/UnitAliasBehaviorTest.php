<?php

namespace lujie\alias\behaviors\tests\unit;

use lujie\alias\behaviors\tests\unit\fixtures\TestAliasComponent;
use lujie\alias\behaviors\UnitAliasBehavior;

class UnitAliasBehaviorTest extends \Codeception\Test\Unit
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
            'as unit' => [
                'class' => UnitAliasBehavior::class,
                'aliasProperties' => [
                    'weight' => 'weight_g',
                ],
                'baseUnit' => UnitAliasBehavior::UNIT_WEIGHT_G,
                'displayUnit' => UnitAliasBehavior::UNIT_WEIGHT_KG,
            ]
        ]);

        $testAliasComponent->weight_g = 123456;
        $this->assertEquals(123.456, $testAliasComponent->weight);
        $testAliasComponent->weight = 654.321;
        $this->assertEquals(654321, $testAliasComponent->weight_g);

        $testAliasComponent = new TestAliasComponent([
            'as unit' => [
                'class' => UnitAliasBehavior::class,
                'aliasProperties' => [
                    'length' => 'length_m',
                ],
                'baseUnitAttribute' => 'lengthBaseUnit',
                'displayUnitAttribute' => 'lengthDisplayUnit',
            ]
        ]);

        $testAliasComponent->length_m = 123.456;
        $this->assertEquals(123456, $testAliasComponent->length);
        $testAliasComponent->length = 654321;
        $this->assertEquals(654.321, $testAliasComponent->length_m);
    }
}
