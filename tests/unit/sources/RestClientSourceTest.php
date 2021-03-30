<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\exchange\tests\unit\sources;

use lujie\data\exchange\sources\RestClientSource;
use lujie\extend\tests\unit\mocks\MockRestClient;

class RestClientSourceTest extends \Codeception\Test\Unit
{


    protected function _before()
    {
    }

    protected function _after()
    {
    }

    /**
     * @throws \Throwable
     * @inheritdoc
     */
    public function testMe(): void
    {
        $testData = [
            ['aaa', 'bbb'],
            ['ccc', 'ddd'],
        ];
        MockRestClient::$batchData = [
            'test-data' => $testData
        ];
        $source = new RestClientSource([
            'client' => MockRestClient::class,
            'resource' => 'test-data'
        ]);
        $this->assertEquals($testData, iterator_to_array($source->batch(), false));
        $this->assertEquals(array_merge(...$testData), iterator_to_array($source->each(), false));
    }
}
