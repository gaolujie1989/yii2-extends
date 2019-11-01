<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\recording\tests\unit\mocks;


use lujie\data\exchange\sources\ArraySource;
use lujie\data\exchange\sources\SourceInterface;
use lujie\data\recording\BaseDataRecorder;
use lujie\data\recording\models\DataSource;

/**
 * Class MockDataRecorder
 * @package lujie\data\recording\tests\unit\mocks
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class MockDataRecorder extends BaseDataRecorder
{
    /**
     * @param DataSource $dataSource
     * @return SourceInterface
     * @inheritdoc
     */
    protected function createSource(DataSource $dataSource): SourceInterface
    {
        return new ArraySource(['data' => [
            [
                'id' => 1,
                'createdAt' => 1234567890,
                'updatedAt' => 1334567890,
                'xxx1' => 'xxx11',
                'xxx2' => 'xxx22',
                'yyy' => 'yy123',
            ]
        ]]);
    }
}
