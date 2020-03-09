<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\template\document\engines;


use lujie\data\loader\ArrayDataLoader;
use lujie\extend\helpers\TemplateHelper;
use yii\base\BaseObject;

/**
 * Class SimpleTemplateEngine
 * @package lujie\template\document
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class SimpleTemplateEngine extends BaseObject implements TemplateEngineInterface
{
    /**
     * @var array
     */
    public $templates = [];

    /**
     * @var array
     */
    public $tag = ['{', '}'];

    /**
     * @param array $templates
     * @inheritdoc
     */
    public function setTemplates(array $templates): void
    {
        $this->loader = new ArrayDataLoader();
        $this->loader->data = $templates;
    }

    /**
     * @param string $template
     * @param array $params
     * @return string
     * @inheritdoc
     */
    public function render(string $template, array $params = []): string
    {
        if (empty($this->templates[$template])) {
            return '';
        }
        return TemplateHelper::render($this->templates[$template], $params, $this->tag);
    }
}
