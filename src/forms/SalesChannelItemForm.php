<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\sales\channel\forms;

use lujie\extend\db\FormTrait;
use lujie\extend\helpers\ValueHelper;
use lujie\sales\channel\models\SalesChannelItem;

/**
 * Class SalesChannelOrderForm
 * @package lujie\sales\channel\forms
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class SalesChannelItemForm extends SalesChannelItem
{
    use FormTrait;

    /**
     * @param bool $insert
     * @return bool
     * @inheritdoc
     */
    public function beforeSave($insert):bool
    {
        $this->item_pushed_options = array_map([ValueHelper::class, 'notEmpty'], $this->item_pushed_options ?: []);
        return parent::beforeSave($insert);
    }
}
