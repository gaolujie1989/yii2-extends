<?php
/**
 * @copyright Copyright (c) 2019
 */

use lujie\common\option\providers\QueryOptionProvider;

/**
 * Class OttoCategoryProvider
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class OttoCategoryProvider extends QueryOptionProvider
{
    public $type = 'ottoCategory';

    /**
     * @param string $type
     * @param string|null $key
     * @return array
     * @inheritdoc
     */
    public function getOptions(string $type, ?string $key = null): array
    {

    }
}