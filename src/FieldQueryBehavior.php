<?php
/**
 * Created by PhpStorm.
 * User: Lujie
 * Date: 2019/3/12
 * Time: 15:28
 */

namespace lujie\db\fieldQuery\behaviors;

use yii\base\Behavior;
use yii\base\InvalidArgumentException;
use yii\base\InvalidConfigException;
use yii\db\Query;
use yii\helpers\Inflector;

/**
 * Class FieldQueryBehavior
 * @package lujie\core\behaviors
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
     * @param string $name
     * @param array $params
     * @return Query
     * @inheritdoc
     */
    protected function queryField(string $name, array $params): Query
    {
        /** @var Query $owner */
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
            if ($op) {
                $owner->andWhere([$op, $field, $value]);
            } else {
                $owner->andWhere([$field => $value]);
            }
        }
        return $owner;
    }

    /**
     * @param $name
     * @return Query
     * @inheritdoc
     */
    protected function queryCondition(string $name): Query
    {
        /** @var Query $owner */
        $owner = $this->owner;
        $owner->andWhere($this->queryConditions[$name]);
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
        /** @var Query $owner */
        $owner = $this->owner;
        [$field, $method] = $this->queryReturns[$name];
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
