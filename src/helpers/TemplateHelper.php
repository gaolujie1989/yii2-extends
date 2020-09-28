<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\helpers;

use yii\helpers\ArrayHelper;

/**
 * Class TemplateHelper
 * @package lujie\extend\helpers
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class TemplateHelper
{
    /**
     * @param string $template
     * @param array|object $params
     * @param array $tag
     * @return string
     * @inheritdoc
     */
    public static function render(string $template, $params, array $tag = ['{', '}']): string
    {
        //build regex string like '/{([^{}\s]+)}/'
        $regexStr = '/' . $tag[0] . '([^' . $tag[0] . $tag[1] . '\s]+)' . $tag[1] . '/';
        if (!preg_match_all($regexStr, $template, $matches)) {
            return $template;
        }

        $paramValues = [];
        foreach ($matches[1] as $paramKey) {
            $templateVarKey = $tag[0] . $paramKey . $tag[1];
            $paramValues[$templateVarKey] = ArrayHelper::getValue($params, $paramKey);
        }
        return strtr($template, $paramValues);
    }

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
}
