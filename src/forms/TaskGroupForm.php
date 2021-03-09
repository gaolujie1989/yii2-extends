<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\project\forms;

use lujie\ar\relation\behaviors\RelationDeletableBehavior;
use lujie\extend\base\FormTrait;
use lujie\project\models\TaskGroup;

/**
 * Class TaskGroupForm
 * @package lujie\project\forms
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class TaskGroupForm extends TaskGroup
{
    use FormTrait;

    /**
     * @return array
     * @inheritdoc
     */
    public function behaviors(): array
    {
        return array_merge(parent::behaviors(), [
            'relationDelete' => [
                'class' => RelationDeletableBehavior::class,
                'relations' => ['tasks']
            ]
        ]);
    }
}
