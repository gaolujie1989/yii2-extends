<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\db;

use Yii;
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
            $message = Yii::t('lujie/extend', 'Readonly mode, write is not supported');
            throw new NotSupportedException($message);
        }
        return parent::execute();
    }
}