<?php
/**
 * Created by PhpStorm.
 * User: Lujie
 * Date: 2019/3/19
 * Time: 11:34
 */

namespace lujie\state\machine\behaviors;

use yii\base\Behavior;
use yii\db\BaseActiveRecord;

/**
 * Class StatusCheckerBehavior
 *
 * @property BaseActiveRecord $owner
 *
 * @package lujie\core\behaviors
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class StatusCheckBehavior extends Behavior
{
    /**
     * @var string
     */
    public $statusAttribute = 'status';

    /**
     * [
     *      'property1' => ['status1', 'status2']
     *      'property2' => ['status3']
     * ]
     * @var array
     */
    public $statusCheckProperties = [];

    /**
     * @var string
     */
    public $statusCheckMethodPrefix = 'get';

    /**
     * @param string $name
     * @param bool $checkVars
     * @return bool
     * @inheritdoc
     */
    public function canGetProperty($name, $checkVars = true): bool
    {
        if ($this->isStatusCheckProperty($name)) {
            return true;
        }
        return parent::canGetProperty($name, $checkVars);
    }

    /**
     * @param string $name
     * @return bool
     * @inheritdoc
     */
    public function hasMethod($name): bool
    {
        if ($this->isStatusCheckMethod($name)) {
            return true;
        }
        return parent::hasMethod($name);
    }

    /**
     * @param string $name
     * @return bool|mixed
     * @throws \yii\base\UnknownPropertyException
     * @inheritdoc
     */
    public function __get($name)
    {
        if ($this->isStatusCheckProperty($name)) {
            return $this->isStatus($name);
        }
        return parent::__get($name);
    }

    /**
     * @param string $name
     * @param array $params
     * @return bool|mixed
     * @inheritdoc
     */
    public function __call($name, $params)
    {
        if ($this->isStatusCheckMethod($name)) {
            $property = lcfirst(substr($name, strlen($this->statusCheckMethodPrefix)));
            return $this->isStatus($property);
        }
        return parent::__call($name, $params);
    }

    /**
     * @param $name
     * @return bool
     * @inheritdoc
     */
    protected function isStatusCheckMethod($name): bool
    {
        if (strpos($name, $this->statusCheckMethodPrefix) === 0) {
            $property = lcfirst(substr($name, strlen($this->statusCheckMethodPrefix)));
            if (isset($this->statusCheckProperties[$property])) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param $name
     * @return bool
     * @inheritdoc
     */
    protected function isStatusCheckProperty($name): bool
    {
        return isset($this->statusCheckProperties[$name]);
    }

    /**
     * @param $name
     * @return bool
     * @inheritdoc
     */
    protected function isStatus($name): bool
    {
        $attribute = $this->owner->getAttribute($this->statusAttribute);
        return in_array($attribute, $this->statusCheckProperties[$name], true);
    }
}
