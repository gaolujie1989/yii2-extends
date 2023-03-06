<?php

namespace lujie\data\exchange\tests\unit\transformers;

use lujie\data\exchange\transformers\KeyMapTransformer;

class KeyMapTransformerTest extends \Codeception\Test\Unit
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
            ['columnA' => 'AAA1', 'columnB' => 'BBB1'],
            ['columnA' => 'AAA2', 'columnB' => 'BBB2'],
            ['columnA' => 'AAA3', 'columnB' => 'BBB3'],
        ];
        $transformer = new KeyMapTransformer([
            'keyMap' => [
                'columnA' => 'columnC'
            ],
            'unsetOriginalKey' => true,
            'unsetNotInMapKey' => true,
        ]);
        $transformedData = $transformer->transform($data);
        $expectedData = [
            ['columnC' => 'AAA1'],
            ['columnC' => 'AAA2'],
            ['columnC' => 'AAA3'],
        ];
        $this->assertEquals($expectedData, $transformedData);

        $transformer = new KeyMapTransformer([
            'keyMap' => [
                'columnA' => 'columnC'
            ],
            'unsetOriginalKey' => false,
            'unsetNotInMapKey' => false,
        ]);
        $transformedData = $transformer->transform($data);
        $expectedData = [
            ['columnC' => 'AAA1', 'columnA' => 'AAA1', 'columnB' => 'BBB1'],
            ['columnC' => 'AAA2', 'columnA' => 'AAA2', 'columnB' => 'BBB2'],
            ['columnC' => 'AAA3', 'columnA' => 'AAA3', 'columnB' => 'BBB3'],
        ];
        $this->assertEquals($expectedData, $transformedData);
    }
}
