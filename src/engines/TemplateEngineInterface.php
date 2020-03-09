<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\template\document\engines;

/**
 * Interface TemplateEngineInterface
 * @package lujie\template\document\engines
 */
interface TemplateEngineInterface
{
    /**
     * @param array $templates
     * @return mixed
     * @inheritdoc
     */
    public function setTemplates(array $templates): void;

    /**
     * @param string $template the template name
     * @param array $params the parameters (name-value pairs) that will be extracted and made available in the template.
     * @return string
     * @inheritdoc
     */
    public function render(string $template, array $params = []): string;
}
