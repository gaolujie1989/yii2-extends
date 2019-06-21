<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\scheduling\tests\unit\mocks;

use Yii;
use yii\base\BaseObject;
use yii\base\Exception;

class TestTask extends BaseObject
{
    public $yKey = 'xxx';

    public $yValue = 'xxx';

    public $throwEx = false;

    /**
     * @throws Exception
     * @inheritdoc
     */
    public function execute(): void
    {
        if ($this->throwEx) {
            throw new Exception('Error');
        }
        Yii::$app->params[$this->yKey] = $this->yValue;
    }
}
