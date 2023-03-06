<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\template\document\engines;

use yii\base\BaseObject;
use yii\di\Instance;
use yii\web\View;

/**
 * Class YiiViewTemplateEngine
 * @package lujie\template\document\engines
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class YiiViewTemplateEngine extends BaseObject implements TemplateEngineInterface
{
    /**
     * @var View
     */
    public $view = ['class' => View::class];

    /**
     * @var bool
     */
    public $renderRawPhpFile = true;

    /**
     * @param string $template
     * @param array $params
     * @return string
     * @throws \yii\base\InvalidConfigException
     * @throws \Exception
     * @inheritdoc
     */
    public function render(string $template, array $params = []): string
    {
        if ($this->view) {
            $this->view = Instance::ensure($this->view, View::class);
        }
        return $this->renderRawPhpFile
            ? $this->view->renderPhpFile($template, $params)
            : $this->view->renderFile($template, $params);
    }
}