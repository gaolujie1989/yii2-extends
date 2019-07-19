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
    public function init(): void
    {
        parent::init();
        $this->initControllerNamespace();
        $this->initViewPath();
    }

    /**
     * @return string
     * @inheritdoc
     */
    protected function getScope(): string
    {
        return Yii::$app->params[$this->scopeKey] ?? Yii::$app->id;
    }

    /**
     * @inheritdoc
     */
    public function initControllerNamespace(): void
    {
        $this->controllerNamespace = $this->controllerNamespace . '\\' . $this->getScope();
    }

    /**
     * @inheritdoc
     */
    public function initViewPath(): void
    {
        $this->setViewPath($this->getBasePath() . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . $this->getScope());
    }
}
