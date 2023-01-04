<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\common\option\searches;

use lujie\common\option\models\ModelOption;
use lujie\common\option\models\Option;
use lujie\common\option\models\OptionTrait;
use yii\db\ActiveQuery;
use yii\db\BaseActiveRecord;

/**
 * Trait OptionSearchTrait
 * @package lujie\common\option\searches
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
trait OptionSearchTrait
{
    use OptionTrait;

    /**
     * @return array
     * @inheritdoc
     */
    protected function getOptionModelIds(): array
    {
        $optionKey = $this->optionKey ?? 'tags';
        $optionValue = $this->{$optionKey};
        $optionClass = $this->optionClass ?? Option::class;
        $modelOptionClass = $this->modelOptionClass ?? ModelOption::class;
        $optionIds = $optionClass::find()->value($optionValue)->getIds();
        if (empty($optionIds)) {
            return [];
        }
        return $modelOptionClass::find()->optionId($optionIds)->getModelIds();
    }

    /**
     * @param ActiveQuery $query
     * @inheritdoc
     */
    protected function queryOption(ActiveQuery $query): void
    {
        $modelIds = $this->getOptionModelIds();
        if (empty($modelIds)) {
            $query->andWhere('1=2');
            return;
        }
        /** @var BaseActiveRecord $modelClass */
        $modelClass = $query->modelClass;
        $primaryKey = $modelClass::primaryKey();
        $query->andWhere([$primaryKey[0] => $modelIds]);
    }
}