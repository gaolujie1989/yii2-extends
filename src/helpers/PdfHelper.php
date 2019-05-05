<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\helpers;

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
     * @param $file
     * @param $data
     * @param $view
     * @param null $viewPath
     * @param string $render
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public static function writePdf($file, $data, $view, $viewPath = null, $render = 'html2pdf')
    {
        if (file_exists($file)) {
            unlink($file);
        }
        /** @var Manager $render */
        $render = Instance::ensure($render);
        if ($viewPath) {
            $render->setViewPath($viewPath);
        }
        $render->render($view, $data)->saveAs($file);
    }
}
