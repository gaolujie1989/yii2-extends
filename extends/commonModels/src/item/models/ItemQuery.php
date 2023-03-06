<?php

namespace lujie\common\item\models;

use lujie\db\fieldQuery\behaviors\FieldQueryBehavior;

/**
 * This is the ActiveQuery class for [[Item]].
 *
 * @method ItemQuery id($id)
 * @method ItemQuery orderById($sort = SORT_ASC)
 * @method int getId()
 * @method array getIds()
 *
 * @method ItemQuery itemId($itemId)
 * @method ItemQuery itemNo($itemNo)
 * @method ItemQuery itemType($itemType)
 * @method ItemQuery status($status)
 *
 * @method array|Item[] all($db = null)
 * @method array|Item|null one($db = null)
 * @method array|Item[] each($batchSize = 100, $db = null)
 *
 * @see Item
 */
class ItemQuery extends \yii\db\ActiveQuery
{

    /**
     * @return array
     * @inheritdoc
     */
    public function behaviors(): array
    {
        return [
            'fieldQuery' => [
                'class' => FieldQueryBehavior::class,
                'queryFields' => [
                    'itemId' => 'item_id',
                    'itemNo' => 'item_no',
                    'itemType' => 'item_type',
                    'status' => 'status',
                ]
            ]
        ];
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function getItemIds(bool $indexByItemNo = true): array
    {
        if ($indexByItemNo) {
            $this->indexBy('item_no');
        }
        return $this->select(['item_id'])->column();
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function getItemNos(bool $indexByItemId = true): array
    {
        if ($indexByItemId) {
            $this->indexBy('item_id');
        }
        return $this->select(['item_no'])->column();
    }
}
