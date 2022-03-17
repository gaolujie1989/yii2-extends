<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\auth\forms;

use yii\base\Model;

/**
 * Class NewAuthAssignmentForm
 * @package lujie\auth\forms
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class UserAuthAssignmentForm extends Model
{
    public $userId;

    public $itemIds;

    public $itemNames;

    /**
     * @return array
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            [['userId'], 'required'],
            [['userId'], 'validateUserId'],
            [['itemIds'], 'validateItemIds'],
            [['itemNames'], 'validateItemNames'],
        ];
    }

}