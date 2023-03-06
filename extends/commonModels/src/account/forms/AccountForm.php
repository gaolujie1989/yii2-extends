<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\common\account\forms;

use lujie\common\account\models\Account;
use lujie\extend\db\FormTrait;

/**
 * Class AccountForm
 * @package lujie\common\account\forms
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class AccountForm extends Account
{
    use FormTrait;

    /**
     * @return array
     * @inheritdoc
     */
    public function rules(): array
    {
        return array_merge($this->formRules(), [
            [['additional'], 'default', 'value' => []],
            [['additional'], 'safe'],
        ]);
    }

    /**
     * @return bool
     * @inheritdoc
     */
    public function beforeValidate(): bool
    {
        if (empty($this->name)) {
            $this->name = $this->type . '_' . $this->username;
        }
        return parent::beforeValidate();
    }
}
