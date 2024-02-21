<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\db;

use yii\db\ActiveRecord;
use yii\db\BaseActiveRecord;

/**
 * Trait TransactionTrait
 * @package lujie\extend\db
 */
trait TransactionTrait
{
    /**
     * @return array
     * @inheritdoc
     */
    public function transactions(): array
    {
        /** @var ActiveRecord $that */
        $that = $this;
        $opInsert = $that->hasEventHandlers(BaseActiveRecord::EVENT_AFTER_INSERT) ? ActiveRecord::OP_INSERT : 0;
        $opUpdate = $that->hasEventHandlers(BaseActiveRecord::EVENT_AFTER_UPDATE) ? ActiveRecord::OP_UPDATE : 0;
        $opDelete = $that->hasEventHandlers(BaseActiveRecord::EVENT_AFTER_DELETE) ? ActiveRecord::OP_DELETE : 0;
        $isTransaction = $opInsert | $opUpdate | $opDelete;
        return array_fill_keys(array_keys($that->scenarios()), $isTransaction);
    }
}
