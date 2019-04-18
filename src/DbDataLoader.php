<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\loader;


use yii\base\BaseObject;
use yii\db\Connection;
use yii\db\Query;

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
    public $db;

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
     * @param int|string $key
     * @return array|bool|null
     * @inheritdoc
     */
    public function loadByKey($key)
    {
        return (new Query())->from($this->table)->andFilterWhere($this->condition)
            ->andWhere([$this->uniqueKey => $key])
            ->one();
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function loadAll()
    {
        return (new Query())->from($this->table)->andFilterWhere($this->condition)
            ->all();
    }
}
