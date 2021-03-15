<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\tests\unit;

use lujie\extend\tests\unit\mocks\MockActiveRecordSearch;
use yii\helpers\VarDumper;

class SearchTraitTest extends \Codeception\Test\Unit
{
    /**
     * @inheritdoc
     */
    public function testQuery(): void
    {
        MockActiveRecordSearch::$columns = [
            'mock_id', 'mock_value',
            'mock_key', 'mock_no', 'mock_name',
            'created_by', 'created_at', 'updated_by', 'updated_at'
        ];
        $search = new MockActiveRecordSearch();
        $rules = [
            [['mock_id', 'mock_key', 'mock_no', 'mock_name'], 'safe'],
            [['created_at', 'updated_at'], 'each', 'rule' => ['date']],
            [['key'], 'string'],
        ];
        $this->assertEquals($rules, array_values($search->rules()), VarDumper::dumpAsString($search->rules()));

        $search->load([
            'mock_id' => '1,2',
            'mock_key' => [3, 4],
            'mock_no' => 'xxx,ooo',
            'created_at' => ['2021-01-01', '2021-12-01'],
        ], '');
        $query = $search->query();
        $where = [
            'and',
            ['mock_id' => ['1','2']],
            ['OR LIKE', 'mock_id',['1','2']],
            ['>=', 'created_at', '2021-01-01'],
            ['<=', 'created_at', '2021-12-01'],
        ];
        $this->assertEquals($where, $query->where, VarDumper::dumpAsString($query->where));
    }

    /**
     * @inheritdoc
     */
    public function testPrepareArray(): void
    {
        //@TODO
    }
}
