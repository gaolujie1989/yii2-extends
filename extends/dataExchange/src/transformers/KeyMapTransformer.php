<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\exchange\transformers;

use yii\base\BaseObject;
use yii\helpers\ArrayHelper;

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
    public $keyMapFlip = false;

    /**
     * @var bool
     */
    public $unsetOriginalKey = true;

    /**
     * @var bool
     */
    public $unsetNotInMapKey = false;

    /**
     * @var null|mixed
     */
    public $default;

    /**
     * @param array $data
     * @return array
     * @inheritdoc
     */
    public function transform(array $data): array
    {
        $this->formatKeyMap();
        return array_map([$this, 'transformInternal'], $data);
    }

    /**
     * @inheritdoc
     */
    protected function formatKeyMap(): void
    {
        if (!ArrayHelper::isAssociative($this->keyMap)) {
            return;
        }
        $keyMap = [];
        foreach ($this->keyMap as $form => $to) {
            $keyMap[] = $this->keyMapFlip ? [$to, $form] : [$form, $to];
        }
        $this->keyMap = $keyMap;
    }

    /**
     * @param $values
     * @return array
     * @inheritdoc
     */
    protected function transformInternal($values): array
    {
        if (is_object($values)) {
            $values = ArrayHelper::toArray($values);
        }
        foreach ($this->keyMap as [$from, $to]) {
            if ($from === $to) {
                continue;
            }
            if (array_key_exists($from, $values)) {
                if ($values[$from] || empty($values[$to])) {
                    $values[$to] = $values[$from];
                }
                if ($this->unsetOriginalKey) {
                    unset($values[$from]);
                }
            } else if (!array_key_exists($to, $values)) {
                $values[$to] = $this->default;
            }
        }
        if ($this->unsetNotInMapKey) {
            $mappedKeys = array_flip(ArrayHelper::getColumn($this->keyMap, 1));
            $values = array_intersect_key($values, $mappedKeys);
        }
        return $values;
    }
}
