<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\common\option\controllers\rest;

use lujie\common\option\models\Option;
use lujie\common\option\OptionManager;
use lujie\extend\rest\ActiveController;
use yii\di\Instance;
use yii\web\NotFoundHttpException;

/**
 * Class ModelOptionController
 * @package lujie\common\option\controllers\rest
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class OptionController extends ActiveController
{
    /**
     * @var string
     */
    public $modelClass = Option::class;

    /**
     * @var OptionManager
     */
    public $optionManager = 'optionManager';

    /**
     * @param string $type
     * @param string $key
     * @return array
     * @throws NotFoundHttpException
     * @throws \yii\base\InvalidConfigException
     * @throws \Exception
     * @inheritdoc
     */
    public function actionOptions(string $type, string $key = ''): array
    {
        $this->optionManager = Instance::ensure($this->optionManager, OptionManager::class);
        return $this->optionManager->getOptions($type, $key);
    }
}
