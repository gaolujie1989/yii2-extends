<?php

namespace lujie\data\exchange\tests\unit;

use lujie\data\exchange\transformers\FillPreValueTransformer;
use lujie\data\exchange\transformers\KeyMapTransformer;

class FillPreValueTransformerTest extends \Codeception\Test\Unit
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
        $data = [
            ['columnA' => 'AAA1', 'columnB' => 'BBB1', 'value' => 1],
            ['columnA' => '', 'columnB' => '', 'value' => 2],
            ['columnA' => 'AAA3', 'columnB' => 'BBB3', 'value' => 3],
            ['columnA' => '', 'columnB' => '', 'value' => 4],
        ];
        $transformer = new FillPreValueTransformer([
            'onlyKeys' => [],
            'excludeKeys' => [],
        ]);
        $transformedData = $transformer->transform($data);
        $expectedData = [
            ['columnA' => 'AAA1', 'columnB' => 'BBB1', 'value' => 1],
            ['columnA' => 'AAA1', 'columnB' => 'BBB1', 'value' => 2],
            ['columnA' => 'AAA3', 'columnB' => 'BBB3', 'value' => 3],
            ['columnA' => 'AAA3', 'columnB' => 'BBB3', 'value' => 4],
        ];
        $this->assertEquals($expectedData, $transformedData);

        $transformer = new FillPreValueTransformer([
            'onlyKeys' => ['columnA'],
            'excludeKeys' => [],
        ]);
        $transformedData = $transformer->transform($data);
        $expectedData = [
            ['columnA' => 'AAA1', 'columnB' => 'BBB1', 'value' => 1],
            ['columnA' => 'AAA1', 'columnB' => '', 'value' => 2],
            ['columnA' => 'AAA3', 'columnB' => 'BBB3', 'value' => 3],
            ['columnA' => 'AAA3', 'columnB' => '', 'value' => 4],
        ];
        $this->assertEquals($expectedData, $transformedData);

        $transformer = new FillPreValueTransformer([
            'onlyKeys' => [],
            'excludeKeys' => ['columnB'],
        ]);
        $transformedData = $transformer->transform($data);
        $this->assertEquals($expectedData, $transformedData);
    }
}
