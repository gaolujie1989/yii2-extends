<?php
/**
 * Created by PhpStorm.
 * User: Lujie
 * Date: 2019/3/19
 * Time: 11:34
 */

namespace lujie\state\machine;


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
    public $statusCheckProperty = [];

    /**
     * Override canSetProperty method to be able to detect the timestamp attributes
     * @inheritdoc
     */
    public function canGetProperty($name, $checkVars = true)
    {
        if (isset($this->statusCheckProperty[$name])) {
            return true;
        }
        return parent::canGetProperty($name, $checkVars);
    }

    /**
     * @param string $name
     * @return bool
     * @inheritdoc
     */
    public function hasMethod($name)
    {
        if (substr($name, 0, 3) === 'get') {
            $property = lcfirst(substr($name, 3));
            if (isset($this->statusCheckMethods[$property])) {
                return true;
            }
        }
        return parent::hasMethod($name);
    }

    public function __get($name)
    {
        if (isset($this->statusCheckProperty[$name])) {
            return in_array($this->owner->getAttribute($this->statusAttribute), $this->statusCheckProperty[$name]);
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
        if (substr($name, 0, 3) === 'get') {
            $property = lcfirst(substr($name, 3));
            if (isset($this->statusCheckMethods[$property])) {
                return in_array($this->owner->getAttribute($this->statusAttribute), $this->statusCheckProperty[$property]);
            }
        }
        parent::__call($name, $params);
    }
}
