<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\helpers;


use Yii;

/**
 * Class BaseFileHelper
 * @package lujie\extend\helpers
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class FileHelper
{
    /**
     * @param string $suffix
     * @param string $prefix
     * @param string $template
     * @return string
     * @throws \Exception
     * @inheritdoc
     */
    public static function generateRandomFileName(string $suffix = '.xxx', string $prefix = 'tmp_',
                                                  string $template = '{prefix}{datetime}_{rand}{suffix}'): string
    {
        return strtr($template, [
            '{prefix}' => $prefix,
            '{suffix}' => $suffix,
            '{datetime}' => date('YmdHis'),
            '{rand}' => random_int(1000, 9999),
        ]);
    }

    /**
     * @param string $file
     * @param string $fileName
     * @return bool
     * @inheritdoc
     */
    public static function sendFile(string $file, string $fileName, $inline = false): bool
    {
        $response = Yii::$app->getResponse();
        $response->format = $response::FORMAT_HTML;
        $response->sendFile($file, $fileName, ['inline' => $inline]);
        return true;
    }
}
