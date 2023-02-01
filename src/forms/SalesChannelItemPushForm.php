<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\sales\channel\forms;

use lujie\extend\db\FormTrait;
use lujie\sales\channel\models\SalesChannelItem;

/**
 * Class SalesChannelItemPushForm
 * @package lujie\sales\channel\forms
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class SalesChannelItemPushForm extends SalesChannelItemForm
{
    /**
     * @return array
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            [['item_type', 'item_id', 'sales_channel_account_id'], 'required'],
            [['sales_channel_account_id', 'item_id'], 'integer'],
            [['item_type'], 'string', 'max' => 20],
        ];
    }

    /**
     * @return bool
     * @inheritdoc
     */
    public function push(): bool
    {
        $salesChannelItem = SalesChannelItemForm::find()
            ->salesChannelAccountId($this->sales_channel_account_id)
            ->itemType($this->item_type)
            ->itemId($this->item_id)
            ->one();
        if ($salesChannelItem === null) {
            $salesChannelItem = new SalesChannelItemForm();
            $salesChannelItem->sales_channel_account_id = $this->sales_channel_account_id;
            $salesChannelItem->item_type = $this->item_type;
            $salesChannelItem->item_id = $this->item_id;
        }
        $salesChannelItem->item_updated_at = time();
        return $salesChannelItem->save(false);
    }
}
