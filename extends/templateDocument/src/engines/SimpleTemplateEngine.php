<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\template\document\engines;

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
    public $tag = ['{', '}'];

    /**
     * @param string $template
     * @param array $params
     * @return string
     * @inheritdoc
     */
    public function render(string $template, array $params = []): string
    {
        return TemplateHelper::render($template, $params, $this->tag);
    }
}
