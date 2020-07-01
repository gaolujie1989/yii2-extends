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
class MapAttributeHistoryHandler extends BaseAttributeHistoryHandler
{
    /**
     * @var DataLoaderInterface
     */
    public $labelLoader;

    /**
     * @param int|string $oldValue
     * @param int|string $newValue
     * @return array|null
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function diff($oldValue, $newValue): ?array
    {
        if ($oldValue === $newValue) {
            return null;
        }
        $this->labelLoader = Instance::ensure($this->labelLoader, DataLoaderInterface::class);
        $multiValues = $this->labelLoader->multiGet([$oldValue, $newValue]);
        $oldLabel = $multiValues[$oldValue] ?? $oldValue;
        $newLabel = $multiValues[$newValue] ?? $newValue;
        return parent::diff($oldLabel, $newLabel);
    }
}