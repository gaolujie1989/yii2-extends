<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\exchange\sources;

use yii\base\InvalidConfigException;
use yii\db\Query;

/**
 * Class DbSource
 * @package lujie\data\exchange\sources
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class DbSource extends QuerySource
{
    /**
     * @var string
     */
    public $table;

    /**
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function init(): void
    {
        if (empty($this->query)) {
            $this->query = (new Query())->from($this->table);
        }
        parent::init();
    }
}
