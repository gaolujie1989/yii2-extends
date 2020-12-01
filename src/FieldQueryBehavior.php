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

/**
 * Class FieldQueryBehavior
 * owner default methods:
 * @method static id($id)
 * @method static orderById($sort)
 * @method int getId()
 * @method array getIds()
 *
 * @property ActiveQuery $owner
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

    /**
     * ex. [
     *      'methodName' => 'attribute0'
     *      'methodName' => ['attribute1' => '>', 'attribute2']
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
     *      'methodName' => ['attribute1', self::RETURN_XXX]
     *      'getAbcList' => ['attribute1', self::RETURN_COLUMN]
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
    private $_alias;

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
            $this->queryFields['id'] = $primaryKey;
            $this->querySorts['orderById'] = $primaryKey;
            $this->queryReturns['getId'] = [reset($primaryKey), self::RETURN_SCALAR];
            $this->queryReturns['getIds'] = [reset($primaryKey), self::RETURN_COLUMN];
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
        if (isset($this->queryReturns[$name])) {
            return $this->queryReturn($name);
        }

        return parent::__call($name, $params);
    }

    /**
     * @return string
     * @inheritdoc
     */
    protected function getAlias(): string
    {
        if ($this->_alias === null) {
            $this->_alias = '';
            $owner = $this->owner;
            if (empty($owner->from)) {
                $this->_alias = '';
            } else if (count($owner->from) === 1) {
                $alias = array_keys($owner->from)[0];
                $this->_alias = is_string($alias) ? $alias : '';
            } else if ($owner instanceof ActiveQueryInterface && count($owner->from) > 1) {
                /** @var ActiveRecord $modelClass */
                $modelClass = $owner->modelClass;
                foreach ($owner->from as $alias => $tableName) {
                    if (is_string($alias) && $tableName === $modelClass::tableName()) {
                        $this->_alias = $alias;
                    }
                }
            }
        }
        return $this->_alias;
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
        } else if (is_array($condition)) {
            if (isset($condition[1]) && is_string($condition[1])) {
                $condition[1] = $this->buildAliasField($condition[1]);
            } else if (isset($condition[0])
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
                Yii::info("Query {$name} of {$this->owner->modelClass} condition value is empty array, set condition 1=2", __METHOD__);
                $owner->andWhere('1=2');
            } else {
                $field = $this->buildAliasField($field);
                if ($op) {
                    $owner->andWhere([$op, $field, $value]);
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
     * @return array|false|mixed|string|null
     * @throws InvalidConfigException
     * @inheritdoc
     */
    protected function queryReturn(string $name)
    {
        $owner = $this->owner;
        [$field, $method] = $this->queryReturns[$name];
        $field = $this->buildAliasField($field);
        switch ($method) {
            case self::RETURN_COLUMN:
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
