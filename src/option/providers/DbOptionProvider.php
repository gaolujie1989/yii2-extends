<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\common\option\providers;

use yii\base\InvalidConfigException;
use yii\db\ActiveRecord;
use yii\db\Connection;
use yii\db\Query;
use yii\di\Instance;

/**
 * Class DbOptionProvider
 * @package lujie\common\option\providers
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class DbOptionProvider extends QueryOptionProvider
{
    /**
     * @var Connection
     */
    public $db = 'db';

    /**
     * @var ?string
     */
    public $table;

    /**
     * @var ActiveRecord|string|null
     */
    public $modelClass;

    /**
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function init(): void
    {
        if ($this->modelClass) {
            $this->table = $this->modelClass::tableName();
            $this->db = $this->modelClass::getDb();
        }
        $this->db = Instance::ensure($this->db, Connection::class);
        if (empty($this->query)) {
            $this->query = (new Query())->from($this->table);
        }
        parent::init();
    }
}