<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\controllers\console;

use lujie\extend\dev\DependencyChecker;
use lujie\extend\gii\VueViewGenerator;
use Yii;
use yii\base\InvalidArgumentException;
use yii\console\Controller;
use yii\di\Instance;

/**
 * Class DevController
 * @package console\controllers
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class DevController extends Controller
{
    public $dependencyChecker = [];

    /**
     * @param string $name
     * @inheritdoc
     */
    public function actionGenerateVueView(string $name): void
    {
        if (empty(Yii::$app->params['vueViews'][$name])) {
            throw new InvalidArgumentException('Vue View not found');
        }
        $config = Yii::$app->params['vueViews'][$name];
        $vueViewGenerator = new VueViewGenerator($config);
        echo $vueViewGenerator->generate();
    }

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
            print_r($this->dependencyChecker->getInvalidNs());
        }
    }
}
