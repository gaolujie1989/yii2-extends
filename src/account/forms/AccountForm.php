<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\common\account\forms;

use lujie\common\account\models\Account;

/**
 * Class AccountForm
 * @package lujie\common\account\forms
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class AccountForm extends Account
{
    /**
     * @return array
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            [['url', 'username', 'password'], 'default', 'value' => ''],
            [['options', 'additional'], 'default', 'value' => []],
            [['status'], 'default', 'value' => 0],
            [['options', 'additional'], 'safe'],
            [['status'], 'integer'],
            [['type'], 'string', 'max' => 50, 'when' => function () {
                return $this->getIsNewRecord();
            }],
            [['name'], 'string', 'max' => 100],
            [['url', 'username', 'password'], 'string', 'max' => 255],
            [['name'], 'unique', 'targetAttribute' => ['name']],
            [['type', 'username'], 'unique', 'targetAttribute' => ['type', 'username']],
        ];
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
