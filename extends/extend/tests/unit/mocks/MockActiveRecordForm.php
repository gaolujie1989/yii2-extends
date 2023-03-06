<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\tests\unit\mocks;

use lujie\alias\behaviors\AliasPropertyBehavior;
use lujie\alias\behaviors\TimestampAliasBehavior;
use lujie\ar\relation\behaviors\RelationSavableBehavior;
use lujie\extend\db\FormTrait;

/**
 * Class MockActiveQuery
 * @package lujie\extend\tests\unit\mocks
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class MockActiveRecordForm extends MockActiveRecord
{
    use FormTrait;

    /**
     * @return array
     * @inheritdoc
     */
    public function behaviors(): array
    {
        return array_merge(parent::behaviors(), [
            'alias' => [
                'class' => AliasPropertyBehavior::class,
                'aliasProperties' => [
                    'mock_price' => 'additional.mock_price',
                ],
            ],
            'tsAlias' => [
                'class' => TimestampAliasBehavior::class,
                'aliasProperties' => [
                    'created_time' => 'created_at',
                    'updated_time' => 'updated_at',
                ],
            ],
            'relationSave' => [
                'class' => RelationSavableBehavior::class,
                'relations' => ['mockCopy']
            ]
        ]);
    }
}
