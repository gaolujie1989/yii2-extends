<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\db;

/**
 * Trait RelationExtraFieldsTrait
 *
 * @property array $relations = []
 *
 * @package lujie\extend\db
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
trait RelationExtraFieldsTrait
{
    /**
     * @return array
     * @inheritdoc
     */
    public function extraFields(): array
    {
        return $this->relationExtraFields();
    }

    /**
     * @return array
     * @inheritdoc
     */
    protected function relationExtraFields(): array
    {
        return array_merge(parent::extraFields(), $this->relations ?? []);
    }
}