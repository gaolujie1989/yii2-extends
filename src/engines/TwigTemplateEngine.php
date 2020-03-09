<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\template\document\engines;


use Twig\Environment;
use Twig\Loader\ArrayLoader;
use Twig\Loader\LoaderInterface;
use yii\base\BaseObject;

/**
 * Class YiiViewTemplateEngine
 * @package lujie\template\document
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class TwigTemplateEngine extends BaseObject implements TemplateEngineInterface
{
    /**
     * @var Environment
     */
    public $twig;

    /**
     * @var LoaderInterface
     */
    public $loader;

    /**
     * @var array
     */
    public $templates = [];

    /**
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        if ($this->loader === null) {
            $this->loader = new ArrayLoader($this->templates);
        }
        if ($this->twig === null) {
            $this->twig = new Environment($this->loader);
        }
    }

    /**
     * @param array $templates
     * @inheritdoc
     */
    public function setTemplates(array $templates): void
    {
        $this->templates = $templates;
        $this->loader = new ArrayLoader($this->templates);
        $this->twig->setLoader($this->loader);
    }

    /**
     * @param string $template
     * @param array $params
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     * @inheritdoc
     */
    public function render(string $template, array $params = []): string
    {
        $this->twig->render($template, $params);
    }
}
