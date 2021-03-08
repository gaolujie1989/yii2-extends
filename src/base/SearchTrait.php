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
     * @return array
     * @inheritdoc
     */
    public function rules(): array
    {
        /** @var $this BaseActiveRecord */
        return ModelHelper::searchRules($this);
    }

    /**
     * @return ActiveQueryInterface
     * @inheritdoc
     */
    public function query(): ActiveQueryInterface
    {
        /** @var $this BaseActiveRecord */
        return ModelHelper::query($this);
    }
}
