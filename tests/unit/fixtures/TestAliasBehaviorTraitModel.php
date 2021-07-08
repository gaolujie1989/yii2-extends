<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\alias\behaviors\tests\unit\fixtures;

use lujie\alias\behaviors\AliasBehaviorTrait;
use yii\base\Model;

/**
 * Class TestAliasModel
 * @package lujie\db\alias\behaviors\tests\unit\fixtures
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class TestAliasBehaviorTraitModel extends Model
{
    use AliasBehaviorTrait;

    public $created_at;

    public $updated_at;

    public $weight_g;

    public $length_cm;

    public $price_cent;
}
