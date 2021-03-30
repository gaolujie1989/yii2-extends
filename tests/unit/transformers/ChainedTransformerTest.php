<?php

namespace lujie\data\exchange\tests\unit\transformers;

use lujie\data\exchange\transformers\ChainedTransformer;
use lujie\data\exchange\transformers\KeyMapTransformer;

class ChainedTransformerTest extends \Codeception\Test\Unit
{
    protected function _before()
    {
    }

    protected function _after()
    {
    }

    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function testMe(): void
    {
        $data = [
            ['columnA' => 'AAA1', 'columnB' => 'BBB1'],
            ['columnA' => 'AAA2', 'columnB' => 'BBB2'],
            ['columnA' => 'AAA3', 'columnB' => 'BBB3'],
        ];
        $expectedData = [
            ['columnC' => 'AAA11'],
            ['columnC' => 'AAA21'],
            ['columnC' => 'AAA31'],
        ];

        $transformer = new ChainedTransformer([
            'transformers' => [
                [
                    'class' => KeyMapTransformer::class,
                    'keyMap' => [
                        'columnA' => 'columnC'
                    ],
                    'unsetOriginalKey' => true,
                    'unsetNotInMapKey' => true,
                ],
                [$this, 'transformForTest']
            ]
        ]);
        $transformedData = $transformer->transform($data);
        $this->assertEquals($expectedData, $transformedData);
    }

    public function transformForTest(array $data): array
    {
        foreach ($data as $key => $values) {
            foreach ($values as $k => $v) {
                $data[$key][$k] = $v . '1';
            }
        }
        return $data;
    }
}
