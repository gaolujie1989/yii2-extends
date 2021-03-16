<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\common\option\forms;


use lujie\common\option\models\Option;
use lujie\extend\db\FormTrait;
use lujie\extend\helpers\ModelHelper;
use yii2tech\ar\position\PositionBehavior;

/**
 * Class OptionForm
 * @package lujie\common\option\forms
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class OptionForm extends Option
{
    use FormTrait;

    public $readOnlyOnUpdateAttributes = [];

    /**
     * @var string
     */
    public $parent_key;

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

        ModelHelper::removeAttributesRules($rules, ['parent_id']);
        return array_merge($rules, [
            [['parent_key'], 'linker',
                'targetAttribute' => ['parentKey' => 'key'],
                'linkAttributes' => ['option_id' => 'parent_id']]
        ]);
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