<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\currency\exchanging\tests\unit\fixtures;

use lujie\currency\exchanging\models\CurrencyExchangeRate;
use yii\test\ActiveFixture;

/**
 * Class CurrencyExchangeRateFixture
 * @package lujie\currency\exchanging\tests\unit\fixtures
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class CurrencyExchangeRateFixture extends ActiveFixture
{
    public $modelClass = CurrencyExchangeRate::class;
}
