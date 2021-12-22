<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\common\category\forms;

use lujie\ar\relation\behaviors\RelationDeletableBehavior;
use lujie\common\category\models\Category;
use lujie\extend\db\FormTrait;
use yii2tech\ar\position\PositionBehavior;

/**
 * Class CategoryForm
 * @package lujie\common\category\forms
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class CategoryForm extends Category
{
    use FormTrait;


    /**
     * @return array
     * @inheritdoc
     */
    public function rules(): array
    {
        return array_merge($this->formRules(), [
            [['parent_id', 'name'], 'required'],
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
            ],
            'relationDelete' => [
                'class' => RelationDeletableBehavior::class,
                'relations' => ['children'],
            ],
        ]);
    }
}