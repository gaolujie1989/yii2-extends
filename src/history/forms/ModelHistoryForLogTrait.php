<?php
/**
 * @copyright Copyright (c) 2023
 */

namespace lujie\common\history\forms;

use lujie\common\history\models\ModelHistory;
use lujie\extend\db\FormTrait;

/**
 * Trait ModelHistoryForLogTrait
 * @package lujie\common\history\forms
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
trait ModelHistoryForLogTrait
{
    /**
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        if (empty($this->relations)) {
            $this->relations = [
                'details' => 'changed_attribute'
            ];
        }
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function rules(): array
    {
        return array_merge(parent::rules(), [
            [['details'], 'safe'],
        ]);
    }
}
