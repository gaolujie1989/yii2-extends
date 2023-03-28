<?php
/**
 * Created by PhpStorm.
 * User: Lujie
 * Date: 2019/3/12
 * Time: 15:28
 */

namespace lujie\db\fieldQuery\behaviors;

use Yii;
use yii\base\Behavior;
use yii\base\Component;
use yii\base\InvalidArgumentException;
use yii\base\InvalidConfigException;
use yii\db\ActiveQuery;
use yii\db\ActiveQueryInterface;
use yii\db\ActiveRecord;
use yii\db\BaseActiveRecord;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\helpers\Inflector;
use function RingCentral\Psr7\str;

/**
 * Class FieldQueryBehavior
 * owner default methods:
 * @method static id($id)
 * @method static orderById($sort)
 * @method int getId()
 * @method array getIds()
 *
 * @property Query|ActiveQuery $owner
 *
 * @package lujie\db\fieldQuery\behaviors
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class FieldQueryBehavior extends Behavior
{
    public const RETURN_COLUMN = 'COLUMN';
    public const RETURN_SCALAR = 'SCALAR';
    public const RETURN_MAX = 'MAX';
    public const RETURN_MIN = 'MIN';
    public const RETURN_SUM = 'SUM';
    public const RETURN_AVG = 'AVG';

    public const TYPE_STRING = 'STRING';

    /**
     * ex. [
     *      'methodName' => 'attribute0'
     *      'methodName' => ['attribute1' => '>', 'attribute2' => 'INT/STRING']
     * ]
     * @var array
     */
    public $queryFields = [];

    /**
     * ex. [
     *      'methodName' => ['attribute1' => 'xxx', 'attribute2' => 'xxx']
     *      'methodName' => ['>', 'attribute0', 'xxx']
     * ]
     * @var array
     */
    public $queryConditions = [];

    /**
     * ex. [
     *      'methodName' => 'attribute0'
     *      'orderByXXXYYY' => ['attribute1', 'attribute2']
     * ]
     * @var array
     */
    public $querySorts = [];

    /**
     * ex. [
     *      'methodName' => 'attribute0'
     *      'indexByXXXYYY' => 'attribute1'
     * ]
     * @var array
     */
    public $queryIndexes = [];

    /**
     * ex. [
     *      'methodName' => ['attribute1', self::RETURN_XXX]
     *      'getAbcList' => ['attribute1', self::RETURN_COLUMN, 'indexByAttribute1']
     *      'getAbc' => ['attribute1', self::RETURN_SCALAR]
     *      'maxAbc' => ['attribute1', self::RETURN_MAX]
     *      'avgAbc' => ['attribute1', self::RETURN_AVG]
     * ]
     * @var array
     */
    public $queryReturns = [];

    /**
     * @var ?string
     */
    private $alias;

    /**
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        foreach ($this->queryFields as $key => $queryField) {
            if (!is_array($queryField)) {
                $this->queryFields[$key] = [$queryField];
            }
        }
        foreach ($this->querySorts as $key => $queryField) {
            if (!is_array($queryField)) {
                $this->querySorts[$key] = [$queryField];
            }
        }
    }

    /**
     * @param Component $owner
     * @inheritdoc
     */
    public function attach($owner): void
    {
        parent::attach($owner);
        if ($this->owner instanceof ActiveQueryInterface) {
            /** @var BaseActiveRecord $modelClass */
            $modelClass = $this->owner->modelClass;
            $primaryKey = $modelClass::primaryKey();
            $pk0 = reset($primaryKey);
            $this->queryFields['id'] = $primaryKey;
            $this->querySorts['orderById'] = $primaryKey;
            $this->queryIndexes['indexById'] = $pk0;
            $this->queryReturns['getId'] = [$pk0, self::RETURN_SCALAR];
            $this->queryReturns['getIds'] = [$pk0, self::RETURN_COLUMN];
        }
    }

    /**
     * @inheritdoc
     */
    public function detach(): void
    {
        parent::detach();
        unset($this->queryFields['id'], $this->querySorts['orderById']);
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasMethod($name): bool
    {
        if (isset($this->queryFields[$name])) {
            return true;
        }
        if (isset($this->queryConditions[$name])) {
            return true;
        }
        if (isset($this->querySorts[$name])) {
            return true;
        }
        if (isset($this->queryIndexes[$name])) {
            return true;
        }
        if (isset($this->queryReturns[$name])) {
            return true;
        }

        return parent::hasMethod($name);
    }

    /**
     * @param string $name
     * @param array $params
     * @return array|false|mixed|string|Query|null
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function __call($name, $params)
    {
        if (isset($this->queryFields[$name])) {
            return $this->queryField($name, $params);
        }
        if (isset($this->queryConditions[$name])) {
            return $this->queryCondition($name);
        }
        if (isset($this->querySorts[$name])) {
            return $this->querySort($name, $params);
        }
        if (isset($this->queryIndexes[$name])) {
            return $this->queryIndex($name);
        }
        if (isset($this->queryReturns[$name])) {
            return $this->queryReturn($name, $params);
        }

        return parent::__call($name, $params);
    }

    /**
     * @return string
     * @inheritdoc
     */
    protected function getAlias(): string
    {
        if (empty($this->alias)) {
            $this->alias = '';
            $owner = $this->owner;
            if (empty($owner->from)) {
                $this->alias = '';
            } elseif (count($owner->from) === 1) {
                $alias = array_keys($owner->from)[0];
                $this->alias = is_string($alias) ? $alias : '';
            } elseif ($owner instanceof ActiveQueryInterface) {
                /** @var ActiveRecord $modelClass */
                $modelClass = $owner->modelClass;
                foreach ($owner->from as $alias => $tableName) {
                    if (is_string($alias) && $tableName === $modelClass::tableName()) {
                        $this->alias = $alias;
                    }
                }
            }
        }
        return $this->alias;
    }

    /**
     * @param string $field
     * @return string
     * @inheritdoc
     */
    protected function buildAliasField(string $field): string
    {
        $alias = $this->getAlias();
        if (!$alias) {
            return $field;
        }
        return $alias . '.' . $field;
    }

    /**
     * @param array|string $condition
     * @return array|string
     * @inheritdoc
     */
    protected function buildAliasCondition($condition)
    {
        $alias = $this->getAlias();
        if (!$alias) {
            return $condition;
        }
        if (ArrayHelper::isAssociative($condition)) {
            $newCondition = [];
            foreach ($condition as $field => $value) {
                $newCondition[$this->buildAliasField($field)] = $value;
            }
            $condition = $newCondition;
        } elseif (is_array($condition)) {
            if (isset($condition[1]) && is_string($condition[1])) {
                $condition[1] = $this->buildAliasField($condition[1]);
            } elseif (isset($condition[0])
                && in_array(strtoupper($condition[0]), ['AND', 'OR'], true)) {
                for ($i = count($condition) - 1; $i > 0; $i--) {
                    $condition[$i] = $this->buildAliasCondition($condition[$i]);
                }
            }
        }
        return $condition;
    }

    /**
     * @param string $name
     * @param array $params
     * @return Query
     * @inheritdoc
     */
    protected function queryField(string $name, array $params): Query
    {
        $owner = $this->owner;
        $allowLike = count($this->queryFields[$name]) === 1;
        foreach ($this->queryFields[$name] as $field => $op) {
            if (is_int($field)) {
                $field = $op;
                $op = null;
            }

            if (empty($params)) {
                $paramName = Inflector::camelize($field);
                throw new InvalidArgumentException("{$paramName} must be set");
            }
            $value = array_shift($params);
            if ($value === []) {
                $ownerClass = get_class($this->owner);
                Yii::info("Query {$name} of {$ownerClass} condition value is empty array, set condition 1=2", __METHOD__);
                $owner->andWhere('1=2');
                return $owner;
            }
            $field = $this->buildAliasField($field);

            if ($op === static::TYPE_STRING) {
                if (is_array($value)) {
                    if (!is_string(reset($value))) {
                        $value = array_map(static function ($v) {
                            return (string)$v;
                        }, $value);
                    }
                } else if (!is_string($value)) {
                    $value = (string)$value;
                }
                $op = null;
            }
            if ($op) {
                if (strtoupper($op) === 'BETWEEN') {
                    $value2 = $params ? array_shift($params) : null;
                    if ($value2 === null) {
                        $owner->andWhere(['>=', $field, $value]);
                    } else if ($value === null) {
                        $owner->andWhere(['<=', $field, $value2]);
                    } else if ($value === $value2) {
                        $owner->andWhere([$field => $value]);
                    } else {
                        $owner->andWhere([$op, $field, $value, $value2]);
                    }
                } else {
                    $owner->andWhere([$op, $field, $value]);
                }
            } else {
                $like = $allowLike && $params ? array_shift($params) : null;
                if ($like) {
                    if ($like === 'L') {
                        $owner->andWhere(['LIKE', $field, $value . '%', false]);
                    } else if ($like === 'R') {
                        $owner->andWhere(['LIKE', $field, '%' . $value, false]);
                    } else if (strpos($value, '%') !== false) {
                        $owner->andWhere(['LIKE', $field, $value, false]);
                    } else {
                        $owner->andWhere(['LIKE', $field, $value]);
                    }
                } else {
                    $owner->andWhere([$field => $value]);
                }
            }
        }
        return $owner;
    }

    /**
     * @param string $name
     * @return Query
     * @inheritdoc
     */
    protected function queryCondition(string $name): Query
    {
        $owner = $this->owner;
        $condition = $this->queryConditions[$name];
        $condition = $this->buildAliasCondition($condition);
        $owner->andWhere($condition);
        return $owner;
    }

    /**
     * @param string $name
     * @return Query
     * @inheritdoc
     */
    protected function querySort(string $name, array $params): Query
    {
        /** @var Query $owner */
        $owner = $this->owner;
        foreach ($this->querySorts[$name] as $field) {
            $sort = empty($params) ? SORT_ASC : array_shift($params);
            $field = $this->buildAliasField($field);
            $owner->addOrderBy([$field => $sort]);
        }
        return $owner;
    }

    /**
     * @param string $name
     * @return Query
     * @inheritdoc
     */
    protected function queryIndex(string $name): Query
    {
        /** @var Query $owner */
        $owner = $this->owner;
        $owner->indexBy($this->queryIndexes[$name]);
        return $owner;
    }

    /**
     * @param string $name
     * @return array|false|mixed|string|null
     * @throws InvalidConfigException
     * @inheritdoc
     */
    protected function queryReturn(string $name, array $params)
    {
        $owner = $this->owner;
        [$field, $method] = $this->queryReturns[$name];
        $field = $this->buildAliasField($field);
        switch ($method) {
            case self::RETURN_COLUMN:
                $indexBy = $this->queryReturns[$name][2] ?? null;
                $isIndex = empty($params) || array_shift($params);
                if ($indexBy && $isIndex && empty($owner->indexBy)) {
                    $owner->indexBy($indexBy);
                }
                return $owner->select([$field])->column();
            case self::RETURN_SCALAR:
                return $owner->select([$field])->scalar();
            case self::RETURN_MAX:
                return $owner->max($field);
            case self::RETURN_MIN:
                return $owner->min($field);
            case self::RETURN_SUM:
                return $owner->sum($field);
            case self::RETURN_AVG:
                return $owner->average($field);
            default:
                throw new InvalidConfigException('Invalid return method');
        }
    }
}
