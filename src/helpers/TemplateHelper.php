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
     * @param string $template
     * @param array $data
     * @return string
     * @throws \Exception
     * @inheritdoc
     */
    public static function generate(string $template, array $data = []): string
    {
        $replaces = array_merge([
            '{timestamp}' => time(),
            '{microSecond}' => microtime(true) * 1000 % 1000,
            '{datetime}' => date('YmdHis'),
            '{date}' => date('Ymd'),
            '{time}' => date('His'),
            '{rand}' => random_int(1000, 9999),
            '{rand1}' => random_int(0, 9),
            '{rand2}' => random_int(10, 99),
            '{rand3}' => random_int(100, 999),
            '{rand4}' => random_int(1000, 9999),
            '{rand5}' => random_int(10000, 99999),
            '{rand6}' => random_int(100000, 999999),
        ], $data);
        return strtr($template, $replaces);
    }

    /**
     * @param string $suffix
     * @param string $prefix
     * @param string $template
     * @return string
     * @throws \Exception
     * @deprecated
     * @inheritdoc
     */
    public static function generateRandomFileName(
        string $suffix = '.xxx',
        string $prefix = 'tmp_',
        string $template = '{prefix}{datetime}_{rand}{suffix}'
    ): string
    {
        return strtr($template, [
            '{prefix}' => $prefix,
            '{suffix}' => $suffix,
            '{datetime}' => date('YmdHis'),
            '{rand}' => random_int(1000, 9999),
        ]);
    }
}
