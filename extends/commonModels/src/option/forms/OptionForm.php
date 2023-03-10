<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\common\option\forms;

use lujie\common\option\models\Option;
use lujie\extend\db\FormTrait;
use yii2tech\ar\position\PositionBehavior;

/**
 * Class OptionForm
 * @package lujie\common\option\forms
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class OptionForm extends Option
{
    use FormTrait;

    /**
     * @return array
     * @inheritdoc
     */
    public function rules(): array
    {
        return array_merge($this->formRules(), [
            [['value', 'name'], 'required'],
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
                'groupAttributes' => ['type'],
            ],
        ]);
    }
}
