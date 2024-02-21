<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\controllers\console;

use lujie\extend\dev\DependencyChecker;
use Yii;
use yii\base\InvalidArgumentException;
use yii\console\Controller;
use yii\di\Instance;
use yii\helpers\VarDumper;

/**
 * Class DevController
 * @package console\controllers
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class DevController extends Controller
{
    /**
     * @var DependencyChecker|array
     */
    public $dependencyChecker = [];

    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function actionDependencyCheck(): void
    {
        $this->dependencyChecker = Instance::ensure($this->dependencyChecker, DependencyChecker::class);
        if ($this->dependencyChecker->check()) {
            echo "No Invalid Dependency\n";
        } else {
            VarDumper::dump($this->dependencyChecker->getInvalidNs());
            VarDumper::dump($this->dependencyChecker->getLoopRequired());
        }
    }
}
