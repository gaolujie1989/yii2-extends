<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\loader;


use yii\base\BaseObject;
use yii\db\Connection;
use yii\db\Query;
use yii\di\Instance;

class DbDataStorage extends BaseObject implements DataStorageInterface
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
    }

    /**
     * @param $data
     * @return int|mixed
     * @throws \yii\db\Exception
     * @inheritdoc
     */
    public function save($data)
    {
        $exists = false;
        $condition = [];
        $query = (new Query())->from($this->table)->andFilterWhere($this->condition);
        if ($this->uniqueKey && isset($data[$this->uniqueKey])) {
            $condition = [$this->uniqueKey => $data[$this->uniqueKey]];
            $exists = $query->andWhere($condition)->exists();
        } else {
            $primaryKey = reset($this->db->getTableSchema($this->table)->primaryKey);
            if ($primaryKey && isset($data[$primaryKey])) {
                $condition = [$primaryKey => $data[$primaryKey]];
                $exists = $query->andWhere($condition)->exists();
            }
        }
        if ($exists) {
            return $this->db->createCommand()->insert($this->table, $data)->execute();
        } else {
            return $this->db->createCommand()->update($this->table, $data, $condition)->execute();
        }
    }
}
