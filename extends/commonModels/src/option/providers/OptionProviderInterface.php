<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\common\option\providers;

/**
 * Interface OptionProviderInterface
 * @package lujie\common\option\providers
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
interface OptionProviderInterface
{
    /**
     * @param string $type
     * @return bool
     * @inheritdoc
     */
    public function hasType(string $type): bool;

    /**
     * @param string $type
     * @param string|null $key
     * @return array
     * @inheritdoc
     */
    public function getOptions(string $type, ?string $key = null): array;

    /**
     * @param string $type
     * @param string $value
     * @param array $data
     * @return array
     * @inheritdoc
     */
    public function addOption(string $type, string $value, array $data = []): bool;
}