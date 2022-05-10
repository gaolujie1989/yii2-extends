<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\common\option\providers;

use lujie\data\exchange\transformers\KeyMapTransformer;
use lujie\extend\helpers\QueryHelper;
use yii\base\BaseObject;
use yii\db\ActiveQueryInterface;
use yii\db\Connection;
use yii\db\Query;
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
    public function getOptions(string $type, string $key = '', $like = true): array
    {
        if ($this->db) {
            $this->db = Instance::ensure($this->db);
        }
        $query = clone $this->query;
        $query->andFilterWhere($this->condition);
        if ($this->filterKeys && $key) {
            QueryHelper::filterKey($query, $this->filterKeys, $key, $like);
        }
        if ($query instanceof ActiveQueryInterface) {
            $query->asArray();
        }
        $transformer = new KeyMapTransformer([
            'keyMap' => $this->keyMap,
            'unsetOriginalKey' => true,
            'unsetNotInMapKey' => true,
        ]);
        return $transformer->transform($query->all());
    }
}