<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\console\controllers;

use yii\base\InvalidConfigException;
use yii\console\Controller;
use yii\di\Instance;
use yii\mutex\Mutex;

/**
 * Class LockController
 * @package lujie\extend\console\controllers
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class MutexController extends Controller
{
    /**
     * @param string $key
     * @param string $mutex
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function actionRelease(string $key, string $mutex = 'mutex'): void
    {
        /** @var Mutex $mutexInstance */
        $mutexInstance = Instance::ensure($mutex, Mutex::class);
        $mutexInstance->release($key);
    }
}
