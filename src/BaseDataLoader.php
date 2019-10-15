<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\loader;

use yii\base\BaseObject;
use yii\base\NotSupportedException;

/**
 * Class BaseDataLoader
 * @package lujie\data\loader
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
abstract class BaseDataLoader extends BaseObject implements DataLoaderInterface
{
    /**
     * @param array $keys
     * @return array
     * @inheritdoc
     */
    public function multiGet(array $keys): array
    {
        $values = [];
        foreach ($keys as $key) {
            $values[$key] = $this->get($key);
        }
        return $values;
    }

    /**
     * @return array|null
     * @throws NotSupportedException
     * @inheritdoc
     */
    public function all(): ?array
    {
        throw new NotSupportedException('The method `all` not support for current data loader.');
    }

    /**
     * @param int $batchSize
     * @return \Iterator
     * @throws NotSupportedException
     * @inheritdoc
     */
    public function batch($batchSize = 100): \Iterator
    {
        throw new NotSupportedException('The method `batch` not support for current data loader.');
    }
}
