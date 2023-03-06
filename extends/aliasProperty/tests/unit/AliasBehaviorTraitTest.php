<?php

namespace lujie\alias\behaviors\tests\unit;

use lujie\alias\behaviors\MoneyAliasBehavior;
use lujie\alias\behaviors\tests\unit\fixtures\TestAliasBehaviorTraitModel;
use lujie\alias\behaviors\TimestampAliasBehavior;
use lujie\alias\behaviors\UnitAliasBehavior;
use PHPUnit\Framework\Assert;

/**
 * Class AliasBehaviorTraitTest
 * @package lujie\alias\behaviors\tests\unit
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class AliasBehaviorTraitTest extends \Codeception\Test\Unit
{
    // tests
    public function testMe(): void
    {
        $testAliasBehaviorTrait = new TestAliasBehaviorTraitModel();
        $expected = [
            'timestampAlias' => [
                'class' => TimestampAliasBehavior::class,
                'aliasProperties' => [
                    'created_time' => 'created_at',
                    'updated_time' => 'updated_at',
                ],
            ],
            'moneyAlias' => [
                'class' => MoneyAliasBehavior::class,
                'aliasProperties' => [
                    'price' => 'price_cent',
                ],
            ],
            'weightUnitAlias' => [
                'class' => UnitAliasBehavior::class,
                'baseUnit' => 'g',
                'displayUnit' => 'kg',
                'aliasProperties' => [
                    'weight_kg' => 'weight_g',
                ],
            ],
        ];
        Assert::assertEquals($expected, $testAliasBehaviorTrait->behaviors());
    }
}
