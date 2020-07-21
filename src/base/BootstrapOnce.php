<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\base;

use lujie\extend\helpers\ClassHelper;
use Yii;
use yii\base\Application;

/**
 * Trait BootstrapOnce
 *
 * @method void bootstrapOnce(Application $app);
 *
 * @package lujie\extend\base
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
trait BootstrapOnce
{
    /**
     * @param $app
     */
    public function bootstrap($app): void
    {
        $classShortName = ClassHelper::getClassShortName($this);
        if (empty(Yii::$app->params['bootstrap'][$classShortName])) {
            Yii::$app->params['bootstrap'][$classShortName] = true;
            $this->bootstrapOnce($app);
        }
    }
}