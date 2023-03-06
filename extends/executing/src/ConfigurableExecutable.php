<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\executing;


use yii\base\BaseObject;
use yii\di\Instance;

/**
 * Class ConfigurableExecutable
 * @package lujie\executing
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ConfigurableExecutable extends BaseObject implements ExecutableInterface
{
    use ExecutableTrait;

    /**
     * @var string
     */
    public $callerClass = BaseObject::class;

    /**
     * @var string|array
     */
    public $caller = 'unknown';

    /**
     * @var string
     */
    public $method = 'unknown';

    /**
     * @var array
     */
    public $params = [];

    /**
     * @return mixed|void
     * @inheritdoc
     */
    public function execute()
    {
        $this->caller = Instance::ensure($this->caller, $this->callerClass);
        return call_user_func_array([$this->caller, $this->method], $this->params);
    }
}