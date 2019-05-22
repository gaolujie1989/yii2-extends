<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\exchange\sources;

use yii\base\BaseObject;

/**
 * Class ArraySource
 * @package lujie\data\exchange\sources
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ArraySource extends BaseObject implements SourceInterface
{
    /**
     * @var array
     */
    public $data = [];

    public function all(): array
    {
        return $this->data;
    }
}
