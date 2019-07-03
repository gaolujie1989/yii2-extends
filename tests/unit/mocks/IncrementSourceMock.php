<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\staging\tests\unit\mocks;


use lujie\data\exchange\sources\IncrementSource;

class IncrementSourceMock extends IncrementSource
{
    /**
     * @param $data
     * @return array
     * @inheritdoc
     */
    protected function generateIncrementCondition(): array
    {
        return ['xxx' => 'xxx'];
    }
}
