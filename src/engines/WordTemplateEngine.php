<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\template\document\engines;

use lujie\extend\helpers\TemplateHelper;
use yii\base\BaseObject;

/**
 * Class WordTemplateEngine
 * @package lujie\template\document\engines
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class WordTemplateEngine extends BaseObject implements TemplateEngineInterface
{
    /**
     * @param string $template
     * @param array $params
     * @return string
     * @inheritdoc
     */
    public function render(string $template, array $params = []): string
    {

    }
}