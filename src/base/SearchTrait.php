<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\base;


use lujie\extend\helpers\ModelHelper;
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

    public function query(): ActiveQueryInterface
    {
        /** @var $this BaseActiveRecord */
        return ModelHelper::query($this);
    }

    /**
     * @param array $row
     * @return array
     * @throws \Exception
     * @inheritdoc
     */
    public static function prepareArray(array $row): array
    {
        return ModelHelper::prepareArray($row, static::class);
    }
}
