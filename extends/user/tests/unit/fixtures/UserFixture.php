<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\user\tests\unit\fixtures;

use lujie\user\models\User;
use yii\test\ActiveFixture;

/**
 * Class UserFixture
 * @package lujie\user\tests\unit\fixtures
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class UserFixture extends ActiveFixture
{
    public $modelClass = User::class;
}
