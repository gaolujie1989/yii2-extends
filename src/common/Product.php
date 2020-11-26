<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\sales\channel\common;

use yii\base\BaseObject;

/**
 * Class Product
 * @package lujie\sales\channel\common
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class Product extends BaseObject
{
    public $attributes = [];

    /**
     * @var ProductVariant[]
     */
    public $productVariants = [];
}