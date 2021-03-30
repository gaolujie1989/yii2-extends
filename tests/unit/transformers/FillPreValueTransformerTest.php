<?php

namespace lujie\data\exchange\tests\unit\transformers;

use lujie\data\exchange\transformers\FillPreValueTransformer;

class FillPreValueTransformerTest extends \Codeception\Test\Unit
{


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
        $data = [
            ['columnA' => 'AAA1', 'columnB' => 'BBB1', 'value' => 1],
            ['columnA' => 'AAA2', 'columnB' => '', 'value' => 2],
            ['columnA' => 'AAA3', 'columnB' => 'BBB3', 'value' => 3],
            ['columnA' => '', 'columnB' => '', 'value' => 4],
        ];
        $transformer = new FillPreValueTransformer([
            'indexKey' => 'columnA',
            'onlyKeys' => [],
            'excludeKeys' => [],
        ]);
        $transformedData = $transformer->transform($data);
        $expectedData = [
            ['columnA' => 'AAA1', 'columnB' => 'BBB1', 'value' => 1],
            ['columnA' => 'AAA2', 'columnB' => '', 'value' => 2],
            ['columnA' => 'AAA3', 'columnB' => 'BBB3', 'value' => 3],
            ['columnA' => 'AAA3', 'columnB' => 'BBB3', 'value' => 4],
        ];
        $this->assertEquals($expectedData, $transformedData);

        $transformer = new FillPreValueTransformer([
            'indexKey' => 'columnA',
            'onlyKeys' => ['columnB'],
            'excludeKeys' => [],
        ]);
        $transformedData = $transformer->transform($data);
        $expectedData = [
            ['columnA' => 'AAA1', 'columnB' => 'BBB1', 'value' => 1],
            ['columnA' => 'AAA2', 'columnB' => '', 'value' => 2],
            ['columnA' => 'AAA3', 'columnB' => 'BBB3', 'value' => 3],
            ['columnA' => '', 'columnB' => 'BBB3', 'value' => 4],
        ];
        $this->assertEquals($expectedData, $transformedData);

        $transformer = new FillPreValueTransformer([
            'indexKey' => 'columnA',
            'onlyKeys' => [],
            'excludeKeys' => ['columnB'],
        ]);
        $transformedData = $transformer->transform($data);
        $expectedData = [
            ['columnA' => 'AAA1', 'columnB' => 'BBB1', 'value' => 1],
            ['columnA' => 'AAA2', 'columnB' => '', 'value' => 2],
            ['columnA' => 'AAA3', 'columnB' => 'BBB3', 'value' => 3],
            ['columnA' => 'AAA3', 'columnB' => '', 'value' => 4],
        ];
        $this->assertEquals($expectedData, $transformedData);
    }
}
