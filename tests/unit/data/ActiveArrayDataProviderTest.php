<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\test\unit\data;

use lujie\extend\data\ActiveArrayDataProvider;
use lujie\extend\tests\unit\mocks\MockActiveQuery;
use lujie\extend\tests\unit\mocks\MockActiveRecord;

class ActiveArrayDataProviderTest extends \Codeception\Test\Unit
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
    public function testMe(): void
    {
        $activeArrayDataProvider = new ActiveArrayDataProvider([
            'query' => new MockActiveQuery(MockActiveRecord::class),
        ]);
        $models = $activeArrayDataProvider->getModels();
        $expected = [
            [
                'mock_id' => 1,
                'mock_value' => 'aaa',
                'prepared' => 1,
            ],
            [
                'mock_id' => 2,
                'mock_value' => 'bbb',
                'prepared' => 1,
            ],
        ];
        $this->assertEquals($expected, $models);
    }
}
