<?php
/**
 * @copyright Copyright (c) 2017
 */

namespace lujie\extend\base;

use Yii;

/**
 * Class Module
 * @package lujie\extend\base
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class Module extends \yii\base\Module
{
    public $scopeKey = 'scope';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->initControllerNamespace();
        $this->initViewPath();
    }

    protected function getScope()
    {
        return Yii::$app->params[$this->scopeKey] ?? Yii::$app->id;
    }

    public function initControllerNamespace()
    {
        $this->controllerNamespace = $this->controllerNamespace . '\\' . $this->getScope();
    }

    public function initViewPath()
    {
        $this->setViewPath($this->getBasePath() . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . $this->getScope());
    }
}
