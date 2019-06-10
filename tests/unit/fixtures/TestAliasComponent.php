<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\alias\behaviors\tests\unit\fixtures;

use yii\base\Component;

/**
 * Class TestAliasModel
 * @package lujie\db\alias\behaviors\tests\unit\fixtures
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class TestAliasComponent extends Component
{
    public $propertyA;

    public $created_at;

    public $updated_time;

    public $weight_g;

    public $length_m;

    public $lengthBaseUnit = 'm';

    public $lengthDisplayUnit = 'mm';
}
