<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\ar\history\handlers;

use lujie\data\loader\DataLoaderInterface;
use yii\di\Instance;

/**
 * Class AddressDiffHandler
 * @package lujie\ar\history\handlers
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ArrayAttributeHistoryHandler extends BaseAttributeHistoryHandler
{
    /**
     * @var bool
     */
    public $withKey = false;

    /**
     * @var ?DataLoaderInterface
     */
    public $labelLoader;

    /**
     * @param mixed|array $oldValue
     * @param mixed|array $newValue
     * @return array|null
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function diff($oldValue, $newValue): ?array
    {
        $oldValue = $oldValue ?: [];
        $newValue = $newValue ?: [];
        if ($oldValue === $newValue) {
            return null;
        }
        if ($this->labelLoader) {
            $this->labelLoader = Instance::ensure($this->labelLoader, DataLoaderInterface::class);
            $multiValues = $this->labelLoader->multiGet($oldValue);
            foreach ($oldValue as $key => $value) {
                $oldValue[$key] = $multiValues[$value] ?? $value;
            }
            $multiValues = $this->labelLoader->multiGet($newValue);
            foreach ($newValue as $key => $value) {
                $newValue[$key] = $multiValues[$value] ?? $value;
            }
        }
        if ($this->withKey) {
            $modified = [];
            foreach ($newValue as $key => $value) {
                if (isset($oldValue[$key])) {
                    $modified[$key] = $this->diffValue($oldValue[$key], $value);
                    unset($newValue[$key], $oldValue[$key]);
                }
            }
            $modified = array_filter($modified);
            $diff = array_filter([
                'added' => $newValue,
                'deleted' => $oldValue,
                'modified' => $modified,
            ]);
        } else {
            $diff = array_filter([
                'added' => array_values(array_diff($newValue, $oldValue)),
                'deleted' => array_values(array_diff($oldValue, $newValue)),
            ]);
        }
        return $diff ?: null;
    }
}
