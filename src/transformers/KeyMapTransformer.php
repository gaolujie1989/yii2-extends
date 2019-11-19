<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\exchange\transformers;

use yii\base\BaseObject;

/**
 * Class KeyMappedTransformer
 * @package lujie\data\exchange\transformers
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class KeyMapTransformer extends BaseObject implements TransformerInterface
{
    /**
     * @var array
     */
    public $keyMap = [];

    /**
     * @var bool
     */
    public $unsetOriginalKey = true;

    /**
     * @var bool
     */
    public $unsetNotInMapKey = false;

    /**
     * @param array $data
     * @return array|null
     * @inheritdoc
     */
    public function transform(array $data): array
    {
        return array_map(function($values) {
            foreach ($this->keyMap as $from => $to) {
                if (isset($values[$from])) {
                    $v = &$values[$from];
                    if ($this->unsetOriginalKey) {
                        unset($values[$from]);
                    }
                    $values[$to] = $v;
                }
            }
            if ($this->unsetNotInMapKey) {
                $values = array_intersect_key($values, array_flip($this->keyMap));
            }
            return $values;
        }, $data);
    }
}
