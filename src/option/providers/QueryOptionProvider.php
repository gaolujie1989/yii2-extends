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
    public $orderBy = [];

    /**
     * @var array
     */
    public $filterKeys;

    /**
     * @var array
     */
    public $paramKeys;

    /**
     * @var array
     */
    public $valueKeys;

    /**
     * @var array
     */
    public $keyMap = [];

    /**
     * @var bool
     */
    public $like = true;

    /**
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        if (!isset($this->keyMap['value']) && in_array('value', $this->keyMap, true)) {
            $this->keyMap = array_flip($this->keyMap);
        }
        if ($this->valueKeys === null && isset($this->keyMap['value'])) {
            $this->valueKeys = [$this->keyMap['value']];
        }
    }

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
     * @param string|null $key
     * @param string|null $values
     * @return array
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function getOptions(string $type, ?string $key = null, ?array $values = null, ?array $params = null): array
    {
        $query = $this->getQuery($type, $key, $values, $params);
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
            'keyMapFlip' => true,
            'unsetOriginalKey' => true,
            'unsetNotInMapKey' => true,
        ]);
        return $transformer->transform($data);
    }

    /**
     * @param string $type
     * @param string|null $key
     * @param string|null $values
     * @return QueryInterface
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    protected function getQuery(string $type, ?string $key = null, ?array $values = null, ?array $params = null): QueryInterface
    {
        if ($this->db) {
            $this->db = Instance::ensure($this->db);
        }
        $query = clone $this->query;
        $query->andWhere($this->condition)->addOrderBy($this->orderBy);
        if ($this->filterKeys && $key) {
            QueryHelper::filterKey($query, $this->filterKeys, $key, $this->like);
        }
        if ($this->paramKeys && $params) {
            foreach ($this->paramKeys as $paramKey) {
                QueryHelper::filterKey($query, $paramKey, $params[$paramKey] ?? null);
            }
        }
        if ($this->valueKeys && $values) {
            QueryHelper::filterKey($query, $this->valueKeys, $values);
        } else if ($this->limit) {
            $query->limit($this->limit);
        }
        if ($query instanceof ActiveQueryInterface) {
            $query->asArray();
        }
        if ($this->keyMap) {
            $query->select($this->keyMap)->distinct();
        }
        return $query;
    }
}
