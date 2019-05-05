<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\helpers;


use yii\helpers\ArrayHelper;

class TemplateHelper
{
    /**
     * @param $template
     * @param $params
     * @param array $tag
     * @return string
     * @inheritdoc
     */
    public static function render($template, $params, $tag = ['{', '}'])
    {
        //build regex string like '/{([^{}\s]+)}/'
        $regexStr = '/' . $tag[0] . '([^' . $tag[0] . $tag[1] . '\s]+)' . $tag[1] . '/';
        if (!preg_match_all($regexStr, $template, $matches)) {
            return $template;
        } else {
            $paramValues = [];
            foreach ($matches[1] as $paramKey) {
                $templateVarKey = $tag[0] . $paramKey . $tag[1];
                $paramValues[$templateVarKey] = ArrayHelper::getValue($params, $paramKey);
            }
            return strtr($template, $paramValues);
        }
    }
}
