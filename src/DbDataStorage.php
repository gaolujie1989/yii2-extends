<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\storage;

use lujie\data\loader\DbDataLoader;
use yii\db\Query;

/**
 * Class DbDataStorage
 * @package lujie\data\loader
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class DbDataStorage extends DbDataLoader implements DataStorageInterface
{
    /**
     * @param mixed $key
     * @param mixed $value
     * @return int|mixed
     * @throws \yii\db\Exception
     * @inheritdoc
     */
    public function set($key, $value)
    {
        $condition = ['AND', $this->condition, [$this->key => $key]];
        $exists = (new Query())->from($this->table)
            ->andFilterWhere($condition)
            ->exists($this->db);

        if ($exists) {
            return $this->db->createCommand()->update($this->table, $value, $condition)->execute();
        }
        return $this->db->createCommand()->insert($this->table, $value)->execute();
    }

    /**
     * @param mixed $key
     * @return int|mixed
     * @throws \yii\db\Exception
     * @inheritdoc
     */
    public function remove($key)
    {
        $condition = ['AND', $this->condition, [$this->key => $key]];
        return $this->db->createCommand()
            ->delete($this->table, $condition)
            ->execute();
    }
}
