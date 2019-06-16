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
     * @param $params
     * @param array $tag
     * @return string
     * @inheritdoc
     */
    public static function render(string $template, $params, $tag = ['{', '}']): string
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
}
