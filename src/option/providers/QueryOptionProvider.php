<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\common\option\providers;

use lujie\data\exchange\transformers\KeyMapTransformer;
use lujie\extend\helpers\ActiveDataHelper;
use lujie\extend\helpers\QueryHelper;
use yii\base\BaseObject;
use yii\base\NotSupportedException;
use yii\db\ActiveQuery;
use yii\db\ActiveQueryInterface;
use yii\db\Connection;
use yii\db\Query;
use yii\db\QueryInterface;
use yii\di\Instance;

/**
 * Class QueryOptionProvider
 * @package lujie\common\option\providers
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class QueryOptionProvider extends BaseObject implements OptionProviderInterface
{
    /**
     * @var string
     */
    public $type;

    /**
     * @var Query
     */
    public $query;

    /**
     * @var ?Connection
     */
    public $db;

    /**
     * @var int
     */
    public $limit = 50;

    /**
     * @var array
     */
    public $condition = [];

    /**
     * @var array
     */
    public $filterKeys;

    /**
     * @var array
     */
    public $keyMap = [];

    /**
     * @var bool
     */
    public $like = true;

    /**
     * @param string $type
     * @return bool
     * @inheritdoc
     */
    public function hasType(string $type): bool
    {
        return $this->type === $type;
    }

    /**
     * @param string $type
     * @param string $key
     * @param bool|string $like
     * @return array
     * @inheritdoc
     * @throws \Exception
     */
    public function getOptions(string $type, ?string $key = null): array
    {
        $query = $this->getQuery($type, $key);
        $data = $query->all($this->db);
        if ($query instanceof ActiveQueryInterface) {
            /** @var ActiveQuery $query */
            $data = ActiveDataHelper::typecast($data, $query->modelClass);
        }
        if (empty($this->keyMap)) {
            return $data;
        }
        $transformer = new KeyMapTransformer([
            'keyMap' => $this->keyMap,
            'unsetOriginalKey' => true,
            'unsetNotInMapKey' => true,
        ]);
        return $transformer->transform($data);
    }

    /**
     * @return QueryInterface
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    protected function getQuery(string $type, ?string $key = null): QueryInterface
    {
        if ($this->db) {
            $this->db = Instance::ensure($this->db);
        }
        $query = clone $this->query;
        $query->andFilterWhere($this->condition)->limit($this->limit);
        if ($this->filterKeys && $key) {
            QueryHelper::filterKey($query, $this->filterKeys, $key, $this->like);
        }
        if ($query instanceof ActiveQueryInterface) {
            $query->asArray();
        }
        if ($this->keyMap) {
            $query->select(array_keys($this->keyMap))->distinct();
        }
        return $query;
    }

    /**
     * @param string $type
     * @param string $value
     * @param array $data
     * @return bool
     * @throws NotSupportedException
     * @inheritdoc
     */
    public function addOption(string $type, string $value, array $data = []): bool
    {
        throw new NotSupportedException('QueryOptionProvider not support add option');
    }
}