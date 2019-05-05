<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\helpers;


class RuleHelper
{
    /**
     * @param array $rules
     * @param string $attribute
     * @param null|mixed $rule
     * @return mixed
     * @inheritdoc
     */
    public static function removeAttributesRules($rules, $attributes, $rule = null)
    {
        $attributes = (array) $attributes;
        foreach ($rules as $key => $ruleConfig) {
            [$ruleAttributes, $ruleName] = $ruleConfig;
            if ($rule === null || $rule === $ruleName) {
                if ((is_string($ruleAttributes) && in_array($ruleAttributes, $attributes))) {
                    unset($rules[$key]);
                } else if (is_array($ruleAttributes) && array_intersect($attributes, $ruleAttributes)) {
                    $ruleAttributes = array_diff($ruleAttributes, $attributes);
                    if ($ruleAttributes) {
                        $rules[$key][0] = $ruleAttributes;
                    } else {
                        unset($rules[$key]);
                    }
                }
            }
        }
        return $rules;
    }
}
