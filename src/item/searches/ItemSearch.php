<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\common\item\searches;

use lujie\common\item\models\Item;
use lujie\common\item\models\ItemBarcode;
use lujie\common\item\models\ItemQuery;
use lujie\extend\base\SearchTrait;
use lujie\extend\helpers\ModelHelper;
use lujie\extend\helpers\QueryHelper;
use yii\db\ActiveQueryInterface;

/**
 * Class ItemSearch
 * @package lujie\common\item\models\searches
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ItemSearch extends Item
{
    use SearchTrait;

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
        return array_merge(ModelHelper::searchRules($this), [
            [['barcode', 'name'], 'safe'],
        ]);
    }

    /**
     * @return ActiveQueryInterface|ItemQuery
     * @inheritdoc
     */
    public function query(): ActiveQueryInterface
    {
        /** @var ItemQuery $query */
        $query = ModelHelper::query($this);
        QueryHelper::filterValue($query, ['names' => $this->name], true);

        if ($this->barcode) {
            $query->innerJoinWith(['barcodes b'], false);
            QueryHelper::filterValue($query, ['b.code_text' => $this->barcode], true);
        }
        return $query;
    }

    /**
     * @param array $row
     * @return array
     * @inheritdoc
     */
    public static function prepareArray(array $row): array
    {
        $alias = [
            'weight_kg' => 'weight_g',
            'length_cm' => 'length_mm',
            'width_cm' => 'width_mm',
            'height_cm' => 'height_mm',
        ];
        $relations = [
            'barcodes' => ItemBarcode::class,
        ];
        return ModelHelper::prepareArray($row, static::class, $alias, $relations);
    }
}