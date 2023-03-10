<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\recording\tests\unit\mocks;

use lujie\data\recording\BaseDataSourceGenerator;
use lujie\data\recording\models\DataSource;

/**
 * Class MockDataSourceGenerator
 * @package lujie\data\recording\tests\unit\mocks
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class MockDataSourceGenerator extends BaseDataSourceGenerator
{
    /**
     * @param int $dataAccountId
     * @param string $type
     * @param int $fromTime
     * @param int $toTime
     * @param int $timePrevious
     * @return DataSource
     * @inheritdoc
     */
    protected function createSource(int $dataAccountId, string $type, int $fromTime, int $toTime, $timePrevious = 5): DataSource
    {
        $dataSource = new DataSource();
        $dataSource->data_account_id = $dataAccountId;
        $dataSource->type = $type;
        $dataSource->name = "Account{$dataAccountId},{$type}," . implode('--', [date('c', $fromTime), date('c', $toTime)]);
        $dataSource->condition = [];
        $dataSource->status = $this->sourceStatus;
        $dataSource->save(false);
        return $dataSource;
    }
}
