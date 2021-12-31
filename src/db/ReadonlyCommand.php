<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\db;

use yii\base\NotSupportedException;
use yii\db\Command;

/**
 * Class ReadonlyCommand
 * @package lujie\extend\db
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ReadonlyCommand extends Command
{
    /**
     * @return int
     * @throws NotSupportedException
     * @throws \yii\db\Exception
     * @inheritdoc
     */
    public function execute(): int
    {
        $sql = $this->getSql();
        if (!$this->db->getSchema()->isReadQuery($sql)) {
            throw new NotSupportedException('Only read is support');
        }
        return parent::execute();
    }
}