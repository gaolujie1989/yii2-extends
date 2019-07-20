<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\batch\tests\unit\mocks;

use lujie\batch\BatchForm;

/**
 * Class MigrationBatchFrom
 * @package lujie\batch\tests\unit\mocks
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class MigrationBatchForm extends BatchForm
{
    public $apply_time;

    /**
     * @return array
     * @inheritdoc
     */
    public function attributes(): array
    {
        return ['apply_time'];
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['apply_time'], 'integer']
        ];
    }
}
