<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\helpers;

use yii\base\InvalidConfigException;
use yii\di\Instance;
use yii2tech\html2pdf\Manager;

/**
 * Class PdfHelper
 * @package lujie\extend\helpers
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class PdfHelper
{
    /**
     * @param string $file
     * @param array $data
     * @param string $view
     * @param string|null $viewPath
     * @param string $render
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public static function writePdf(string $file, array $data, string $view, ?string $viewPath = null, string $render = 'html2pdf'): void
    {
        if (file_exists($file)) {
            unlink($file);
        }
        /** @var Manager $renderInstance */
        $renderInstance = Instance::ensure($render, Manager::class);
        if ($viewPath) {
            $renderInstance->setViewPath($viewPath);
        }
        $renderInstance->render($view, $data)->saveAs($file);
    }
}
