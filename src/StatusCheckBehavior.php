<?php
/**
 * Created by PhpStorm.
 * User: Lujie
 * Date: 2019/3/19
 * Time: 11:34
 */

namespace lujie\statemachine;


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
     *      'method1' => ['status1', 'status2']
     *      'method3' => ['status3']
     * ]
     * @var array
     */
    public $statusCheckMethods = [];

    /**
     * @param string $name
     * @return bool
     * @inheritdoc
     */
    public function hasMethod($name)
    {
        if (isset($this->statusCheckMethods[$name])) {
            return true;
        }
        return parent::hasMethod($name);
    }

    /**
     * @param string $name
     * @param array $params
     * @return bool|mixed
     * @inheritdoc
     */
    public function __call($name, $params)
    {
        if (isset($this->statusCheckMethods[$name])) {
            return in_array($this->owner->getAttribute($this->statusAttribute), $this->statusCheckMethods[$name]);
        }
        parent::__call($name, $params);
    }
}
