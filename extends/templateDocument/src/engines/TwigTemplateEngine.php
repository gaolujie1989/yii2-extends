<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\template\document\engines;

use Twig\Environment;
use Twig\Extra\Intl\IntlExtension;
use Twig\Loader\ArrayLoader;
use yii\base\BaseObject;

/**
 * Class YiiViewTemplateEngine
 * @package lujie\template\document
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class TwigTemplateEngine extends BaseObject implements TemplateEngineInterface
{
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
        $loader = new ArrayLoader(['template' => $template]);
        $twig = new Environment($loader);
        if (class_exists(IntlExtension::class)) {
            $twig->addExtension(new IntlExtension());
        }
        return $twig->render('template', $params);
    }
}
