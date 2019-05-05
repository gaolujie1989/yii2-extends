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
use yii\db\Query;
use yii\helpers\Inflector;

/**
 * Class FieldQueryBehavior
 * @package lujie\core\behaviors
 */
class FieldQueryBehavior extends Behavior
{
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
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        foreach ($this->queryFields as $key => $queryField) {
            if (!is_array($queryField)) {
                $this->queryFields[$key] = [$queryField];
            }
        }
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasMethod($name)
    {
        if (isset($this->queryFields[$name])) {
            return true;
        }
        if (isset($this->queryConditions[$name])) {
            return true;
        }

        return parent::hasMethod($name);
    }

    /**
     * @param string $name
     * @param array $params
     * @return mixed|Query
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
        return parent::__call($name, $params);
    }

    /**
     * @param $name
     * @param $params
     * @return Query
     * @inheritdoc
     */
    protected function queryField($name, $params)
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
    protected function queryCondition($name)
    {
        /** @var Query $owner */
        $owner = $this->owner;
        $owner->andWhere($this->queryConditions[$name]);
        return $owner;
    }
}
