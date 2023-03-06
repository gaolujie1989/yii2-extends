<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\helpers;

/**
 * Class ModelRuleHelper
 * @package lujie\extend\helpers
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ModelRuleHelper
{
    /**
     * @param array $rules
     * @param array|string $attributes
     * @param string|null $rule
     * @return array
     * @deprecated
     */
    public static function removeAttributesRules(array &$rules, $attributes, ?string $rule = null): array
    {
        return ModelHelper::removeAttributesRules($rules, $attributes, $rule);
    }
}
