<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\as2\forms;

use lujie\ar\relation\behaviors\RelationDeletableBehavior;
use lujie\ar\relation\behaviors\RelationSavableBehavior;
use lujie\as2\models\As2Message;
use lujie\extend\db\FormTrait;

/**
 * Class As2MessageForm
 * @package lujie\as2\forms
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class As2MessageForm extends As2Message
{
    use FormTrait;

    /**
     * @return array
     * @inheritdoc
     */
    public function rules(): array
    {
        return array_merge($this->formRules(), [
            [['content'], 'safe']
        ]);
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function behaviors(): array
    {
        return array_merge(parent::behaviors(), [
            'relationSave' => [
                'class' => RelationSavableBehavior::class,
                'relations' => ['content']
            ],
            'relationDelete' => [
                'class' => RelationDeletableBehavior::class,
                'relations' => ['content']
            ],
        ]);
    }
}