<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\common\tests\unit\fixtures;

use lujie\common\item\models\Item;
use lujie\common\option\searches\OptionSearchTrait;

/**
 * Class ItemWithOptionSearch
 * @package lujie\common\tests\unit\fixtures
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ItemWithOptionSearch extends Item
{
    use OptionSearchTrait;
}