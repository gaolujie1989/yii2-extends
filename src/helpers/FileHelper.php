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
     * @return string
     * @throws \Exception
     * @inheritdoc
     */
    public static function generateRandomFileName($suffix = '.xxx', $prefix = 'tmp_'): string
    {
        return $prefix . date('YmdHis') . '_' . random_int(1000, 9999) . $suffix;
    }

    /**
     * @param $data
     * @param $fileName
     * @inheritdoc
     */
    public static function sendFile($file, $fileName): bool
    {
        $response = Yii::$app->getResponse();
        $response->format = $response::FORMAT_HTML;
        $response->sendFile($file, $fileName);
        return true;
    }
}
