<?php

namespace lujie\db\fieldQuery\behaviors\tests\unit;

use lujie\db\fieldQuery\behaviors\FieldQueryBehavior;
use lujie\db\fieldQuery\behaviors\tests\unit\fixtures\Migration;

class FieldQueryBehaviorTest extends \Codeception\Test\Unit
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
     * @inheritdoc
     */
    public function testQueryFields(): void
    {
        $query = Migration::find();
        $query->attachBehavior('fieldQuery', [
            'class' => FieldQueryBehavior::class,
            'queryFields' => [
                'fieldAEq' => ['fieldA'],
                'fieldBIn' => ['fieldB' => 'in'],
                'fieldCGtDLt' => ['fieldC' => '>', 'fieldD' => '<'],
                'fieldDBetween' => ['fieldD' => 'between'],
            ]
        ]);
        $query->where = null;
        $query->fieldAEq('A');
        $this->assertEquals(['fieldA' => 'A'], $query->where);

        $query->where = null;
        $query->fieldAEq(['A', 'AA']);
        $this->assertEquals(['fieldA' => ['A', 'AA']], $query->where);

        $query->where = null;
        $query->fieldBIn(['B']);
        $this->assertEquals(['in', 'fieldB', ['B']], $query->where);

        $query->where = null;
        $query->fieldCGtDLt(1, 2);
        $this->assertEquals(['and', ['>', 'fieldC', 1], ['<', 'fieldD', 2]], $query->where);

        $query->where = null;
        $query->fieldDBetween(1, 2);
        $this->assertEquals(['between', 'fieldD', 1, 2], $query->where);


        //test with alias
        $query = Migration::find()->alias('mt');
        $query->attachBehavior('fieldQuery', [
            'class' => FieldQueryBehavior::class,
            'queryFields' => [
                'fieldAEq' => ['fieldA'],
                'fieldBIn' => ['fieldB' => 'in'],
                'fieldCGtDLt' => ['fieldC' => '>', 'fieldD' => '<'],
            ]
        ]);
        $query->where = null;
        $query->fieldAEq('A');
        $this->assertEquals(['mt.fieldA' => 'A'], $query->where);

        $query->where = null;
        $query->fieldAEq(['A', 'AA']);
        $this->assertEquals(['mt.fieldA' => ['A', 'AA']], $query->where);

        $query->where = null;
        $query->fieldBIn(['B']);
        $this->assertEquals(['in', 'mt.fieldB', ['B']], $query->where);

        $query->where = null;
        $query->fieldCGtDLt(1, 2);
        $this->assertEquals(['and', ['>', 'mt.fieldC', 1], ['<', 'mt.fieldD', 2]], $query->where);

        $query->where = null;
        $query->fieldDBetween(1, 2);
        $this->assertEquals(['between', 'mt.fieldD', 1, 2], $query->where);
    }

    // tests
    public function testQueryConditions(): void
    {
        $query = Migration::find();
        $query->attachBehavior('fieldQuery', [
            'class' => FieldQueryBehavior::class,
            'queryConditions' => [
                'fieldAEq' => ['fieldA' => 'A'],
                'fieldBIn' => ['in', 'fieldB', ['B']],
                'fieldCGtDLt' => ['and', ['>', 'fieldC', 1], ['<', 'fieldD', 2]],
            ]
        ]);
        $query->where = null;
        $query->fieldAEq();
        $this->assertEquals(['fieldA' => 'A'], $query->where);

        $query->where = null;
        $query->fieldBIn();
        $this->assertEquals(['in', 'fieldB', ['B']], $query->where);

        $query->where = null;
        $query->fieldCGtDLt();
        $this->assertEquals(['and', ['>', 'fieldC', 1], ['<', 'fieldD', 2]], $query->where);


        //test with alias
        $query = Migration::find()->alias('mt');
        $query->attachBehavior('fieldQuery', [
            'class' => FieldQueryBehavior::class,
            'queryConditions' => [
                'fieldAEq' => ['fieldA' => 'A'],
                'fieldBIn' => ['in', 'fieldB', ['B']],
                'fieldCGtDLt' => ['and', ['>', 'fieldC', 1], ['<', 'fieldD', 2]],
            ]
        ]);
        $query->where = null;
        $query->fieldAEq();
        $this->assertEquals(['mt.fieldA' => 'A'], $query->where);

        $query->where = null;
        $query->fieldBIn();
        $this->assertEquals(['in', 'mt.fieldB', ['B']], $query->where);

        $query->where = null;
        $query->fieldCGtDLt();
        $this->assertEquals(['and', ['>', 'mt.fieldC', 1], ['<', 'mt.fieldD', 2]], $query->where);
    }

    // tests
    public function testQuerySorts(): void
    {
        $query = Migration::find();
        $query->attachBehavior('fieldQuery', [
            'class' => FieldQueryBehavior::class,
            'querySorts' => [
                'orderByFieldA' => ['fieldA'],
                'orderByFieldAB' => ['fieldA', 'fieldB'],
            ]
        ]);
        $query->orderBy = null;
        $query->orderByFieldA();
        $this->assertEquals(['fieldA' => SORT_ASC], $query->orderBy);

        $query->orderBy = null;
        $query->orderByFieldA(SORT_DESC);
        $this->assertEquals(['fieldA' => SORT_DESC], $query->orderBy);

        $query->orderBy = null;
        $query->orderByFieldAB(SORT_DESC);
        $this->assertEquals(['fieldA' => SORT_DESC, 'fieldB' => SORT_ASC], $query->orderBy);

        $query->orderBy = null;
        $query->orderByFieldAB(SORT_DESC, SORT_DESC);
        $this->assertEquals(['fieldA' => SORT_DESC, 'fieldB' => SORT_DESC], $query->orderBy);


        //test with alias
        $query = Migration::find()->alias('mt');
        $query->attachBehavior('fieldQuery', [
            'class' => FieldQueryBehavior::class,
            'querySorts' => [
                'orderByFieldA' => ['fieldA'],
                'orderByFieldAB' => ['fieldA', 'fieldB'],
            ]
        ]);
        $query->orderBy = null;
        $query->orderByFieldA();
        $this->assertEquals(['mt.fieldA' => SORT_ASC], $query->orderBy);

        $query->orderBy = null;
        $query->orderByFieldA(SORT_DESC);
        $this->assertEquals(['mt.fieldA' => SORT_DESC], $query->orderBy);

        $query->orderBy = null;
        $query->orderByFieldAB(SORT_DESC);
        $this->assertEquals(['mt.fieldA' => SORT_DESC, 'mt.fieldB' => SORT_ASC], $query->orderBy);

        $query->orderBy = null;
        $query->orderByFieldAB(SORT_DESC, SORT_DESC);
        $this->assertEquals(['mt.fieldA' => SORT_DESC, 'mt.fieldB' => SORT_DESC], $query->orderBy);
    }

    public function testQueryReturns(): void
    {
        $existApplyTimes = Migration::find()->select(['apply_time'])->column();

        $query = Migration::find();
        $query->attachBehavior('fieldQuery', [
            'class' => FieldQueryBehavior::class,
            'queryReturns' => [
                'getApplyTime' => ['apply_time', FieldQueryBehavior::RETURN_SCALAR],
                'getApplyTimeList' => ['apply_time', FieldQueryBehavior::RETURN_COLUMN],
                'maxApplyTime' => ['apply_time', FieldQueryBehavior::RETURN_MAX],
                'minApplyTime' => ['apply_time', FieldQueryBehavior::RETURN_MIN],
                'sumApplyTime' => ['apply_time', FieldQueryBehavior::RETURN_SUM],
                'avgApplyTime' => ['apply_time', FieldQueryBehavior::RETURN_AVG],
            ]
        ]);

        $applyTime = $query->getApplyTime();
        $this->assertEquals($applyTime, $existApplyTimes[0]);

        $applyTimeList = $query->getApplyTimeList();
        $this->assertEquals($applyTimeList, $existApplyTimes);

        $maxApplyTime = $query->maxApplyTime();
        $this->assertEquals($maxApplyTime, max($existApplyTimes));

        $minApplyTime = $query->minApplyTime();
        $this->assertEquals($minApplyTime, min($existApplyTimes));

        $sumApplyTime = $query->sumApplyTime();
        $this->assertEquals($sumApplyTime, array_sum($existApplyTimes));

        $avgApplyTime = $query->avgApplyTime();
        $existAvgApplyTime = array_sum($existApplyTimes) / count($existApplyTimes);
        $this->assertEquals(round($avgApplyTime), round($existAvgApplyTime));


        //test with alias
        $query = Migration::find()->alias('mt');
        $query->attachBehavior('fieldQuery', [
            'class' => FieldQueryBehavior::class,
            'queryReturns' => [
                'getApplyTime' => ['apply_time', FieldQueryBehavior::RETURN_SCALAR],
                'getApplyTimeList' => ['apply_time', FieldQueryBehavior::RETURN_COLUMN],
                'maxApplyTime' => ['apply_time', FieldQueryBehavior::RETURN_MAX],
                'minApplyTime' => ['apply_time', FieldQueryBehavior::RETURN_MIN],
                'sumApplyTime' => ['apply_time', FieldQueryBehavior::RETURN_SUM],
                'avgApplyTime' => ['apply_time', FieldQueryBehavior::RETURN_AVG],
            ]
        ]);

        $applyTime = $query->getApplyTime();
        $this->assertEquals($applyTime, $existApplyTimes[0]);

        $applyTimeList = $query->getApplyTimeList();
        $this->assertEquals($applyTimeList, $existApplyTimes);

        $maxApplyTime = $query->maxApplyTime();
        $this->assertEquals($maxApplyTime, max($existApplyTimes));

        $minApplyTime = $query->minApplyTime();
        $this->assertEquals($minApplyTime, min($existApplyTimes));

        $sumApplyTime = $query->sumApplyTime();
        $this->assertEquals($sumApplyTime, array_sum($existApplyTimes));

        $avgApplyTime = $query->avgApplyTime();
        $existAvgApplyTime = array_sum($existApplyTimes) / count($existApplyTimes);
        $this->assertEquals(round($avgApplyTime), round($existAvgApplyTime));
    }
}
