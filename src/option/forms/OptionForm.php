<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\common\option\forms;


use lujie\extend\helpers\ModelHelper;
use lujie\common\option\models\Option;
use yii2tech\ar\position\PositionBehavior;

/**
 * Class OptionForm
 * @package lujie\common\option\forms
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class OptionForm extends Option
{
    public $readOnlyOnUpdateAttributes = [];

    /**
     * @return array
     * @inheritdoc
     */
    public function rules(): array
    {
        $rules = parent::rules();
        if ($this->readOnlyOnUpdateAttributes && !$this->getIsNewRecord()) {
            ModelHelper::removeAttributesRules($rules, $this->readOnlyOnUpdateAttributes);
        }
        return $rules;
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function behaviors(): array
    {
        return array_merge(parent::behaviors(), [
            'position' => [
                'class' => PositionBehavior::class,
                'groupAttributes' => ['parent_id'],
            ]
        ]);
    }
}