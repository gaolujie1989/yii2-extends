<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\stock\tests\unit;

use lujie\stock\BaseStockManager;
use lujie\stock\DbStockManager;
use lujie\stock\StockValueBehavior;

class DbStockManagerTest extends ActiveRecordStockManagerTest
{
    /**
     * @return BaseStockManager
     * @inheritdoc
     */
    protected function getStockManager(): BaseStockManager
    {
        return new DbStockManager([
            'as stockValue' => [
                'class' => StockValueBehavior::class
            ]
        ]);
    }
}
