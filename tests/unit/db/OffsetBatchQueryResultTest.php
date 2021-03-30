<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\test\unit\db;

use lujie\extend\helpers\QueryHelper;
use lujie\extend\tests\unit\fixtures\models\Migration;
use lujie\extend\tests\unit\fixtures\searches\MigrationSearch;
use yii\base\InvalidValueException;

class OffsetBatchQueryResultTest extends \Codeception\Test\Unit
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
        $query = MigrationSearch::find()->asArray();
        $each = QueryHelper::each($query, 10, 3);
        $batchFetchedData = iterator_to_array($each, false);
        $queryData = $query->all();
        $this->assertEquals($queryData, $batchFetchedData);
    }
}
