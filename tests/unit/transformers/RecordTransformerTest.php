<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\recording\tests\unit\transformers;

use lujie\data\recording\transformers\RecordTransformer;

class RecordTransformerTest extends \Codeception\Test\Unit
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
        $data = [
            'id' => 'id1',
            'createdAt' => 123123,
            'updatedAt' => 456456,
            'xxx1' => 'xxx11',
            'xxx2' => 'xxx22',
            'yyy' => 'yy123',
        ];
        $transformer = new RecordTransformer([
            'recordConfig' => [
                'data_additional' => [
                    'x1' => 'xxx1',
                    'y' => 'yyy',
                ]
            ]
        ]);

        $expectedRecord = [
            'data_id' => 'id1',
            'data_created_at' => 123123,
            'data_updated_at' => 456456,
            'data_additional' => [
                'x1' => 'xxx11',
                'y' => 'yy123',
            ]
        ];
        $transformedData = $transformer->transform([$data]);
        $this->assertEquals($expectedRecord, $transformedData[0]['record']);
        $unCompressText = $transformer->compressor->unCompress($transformedData[0]['text']);
        $unserializeData = $transformer->serializer->unserialize($unCompressText);
        $this->assertEquals($data, $unserializeData);
    }
}
