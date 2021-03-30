<?php

namespace lujie\alias\behaviors\tests\unit;

use lujie\alias\behaviors\AliasPropertyBehavior;
use lujie\alias\behaviors\tests\unit\fixtures\TestAliasComponent;

class AliasPropertyBehaviorTest extends \Codeception\Test\Unit
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
            'as alias' => [
                'class' => AliasPropertyBehavior::class,
                'aliasProperties' => [
                    'aliasA' => 'propertyA',
                    'aliasArrayA' => 'propertyArray.A'
                ]
            ]
        ]);
        $testAliasComponent->propertyA = 'A';
        $this->assertEquals('A', $testAliasComponent->aliasA);
        $testAliasComponent->aliasA = 'AA';
        $this->assertEquals('AA', $testAliasComponent->propertyA);

        $this->assertNull($testAliasComponent->aliasArrayA);
        $testAliasComponent->aliasArrayA = 'ABC';
        $this->assertEquals(['A' => 'ABC'], $testAliasComponent->propertyArray);
        $this->assertEquals('ABC', $testAliasComponent->aliasArrayA);
    }
}
