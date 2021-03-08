<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\base;


use lujie\extend\helpers\ModelHelper;
use lujie\extend\helpers\QueryHelper;
use yii\db\ActiveQueryInterface;
use yii\db\BaseActiveRecord;

/**
 * Trait SearchTrait
 * @package lujie\extend\base
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
trait SearchTrait
{
    /**
     * @var string
     */
    public $key;

    /**
     * @return array
     * @inheritdoc
     */
    public function rules(): array
    {
        /** @var $this BaseActiveRecord */
        return array_merge(ModelHelper::searchRules($this), [
            [['key'], 'string'],
        ]);
    }

    /**
     * @param array $row
     * @return array
     * @inheritdoc
     */
    public static function prepareArray(array $row): array
    {
        $pk = static::primaryKey();
        $row['id'] = $row[$pk[0]];
        return $row;
    }
}
