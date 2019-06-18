<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\scheduling\tests\unit\mocks;

use Yii;
use yii\base\BaseObject;

class TestTask extends BaseObject
{
    public $yKey = 'xxx';

    public $yValue = 'xxx';

    /**
     * @inheritdoc
     */
    public function execute(): void
    {
        Yii::$app->params[$this->yKey] = $this->yValue;
    }
}
