<?php

namespace lujie\alias\behaviors\tests\unit;

use lujie\alias\behaviors\ArrayTextAliasBehavior;
use lujie\alias\behaviors\tests\unit\fixtures\TestAliasComponent;

class ArrayTextAliasPropertyBehaviorTest extends \Codeception\Test\Unit
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
                'class' => ArrayTextAliasBehavior::class,
                'aliasProperties' => [
                    'propertyArrayText' => 'propertyArray'
                ]
            ]
        ]);
        $testAliasComponent->propertyArrayText = 'aaa, bbb / ccc ; ddd';
        $this->assertEquals(['aaa','bbb','ccc','ddd'], $testAliasComponent->propertyArray);
        $this->assertEquals('aaa,bbb,ccc,ddd', $testAliasComponent->propertyArrayText);
    }
}
