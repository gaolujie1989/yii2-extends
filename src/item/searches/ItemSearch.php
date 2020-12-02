<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\common\item\models\searches;

use Codeception\PHPUnit\Constraint\Page;
use lujie\common\item\models\Item;
use lujie\common\item\models\ItemQuery;

/**
 * Class ItemSearch
 * @package lujie\common\item\models\searches
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ItemSearch extends Item
{
    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $barcode;

    /**
     * @return array
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            [['item_no', 'item_type', 'status'], 'safe'],
            [['name', 'barcode'], 'safe'],
        ];
    }

    public function query(): ItemQuery
    {
        $query = static::find()
            ->andFilterWhere([
                'item_type' => $this->item_type,
                'status' => $this->status,
            ])
            ->andFilterWhere(['LIKE', 'item_no', $this->item_no])
            ->andFilterWhere(['LIKE', 'names', $this->name]);
        if ($this->barcode) {
            $query->innerJoinWith(['barcodes b'], false)->andOnCondition(['LIKE', 'b.code_text', $this->barcode]);
        }
        return $query;
    }
}