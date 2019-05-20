<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\loader;


use function PHPSTORM_META\registerArgumentsSet;
use yii\base\BaseObject;
use yii\db\Connection;
use yii\db\Query;
use yii\di\Instance;

/**
 * Class DbDataLoader
 * @package lujie\data\loader
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class DbDataLoader extends BaseObject implements DataLoaderInterface
{
    /**
     * @var Connection
     */
    public $db = 'db';

    /**
     * @var string
     */
    public $table;

    /**
     * @var string|int
     */
    public $uniqueKey;

    /**
     * @var array
     */
    public $condition = [];

    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->db = Instance::ensure($this->db, Connection::class);
        if (empty($this->uniqueKey)) {
            $this->uniqueKey = reset($this->db->getTableSchema($this->table)->primaryKey);
        }
    }

    /**
     * @param int|string $key
     * @return array|bool|null
     * @inheritdoc
     */
    public function get($key)
    {
        return (new Query())->from($this->table)
            ->andFilterWhere($this->condition)
            ->andWhere([$this->uniqueKey => $key])
            ->one($this->db);
    }

    /**
     * @return array|null
     * @inheritdoc
     */
    public function all(): ?array
    {
        return (new Query())->from($this->table)
            ->andFilterWhere($this->condition)
            ->all($this->db);
    }
}
