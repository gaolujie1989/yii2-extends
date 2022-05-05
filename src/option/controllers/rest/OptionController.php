<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\common\option\controllers\rest;

use lujie\common\option\models\Option;
use lujie\common\option\providers\OptionProviderInterface;
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
    public $modelClass = Option::class;

    /**
     * @var OptionProviderInterface[]
     */
    public $optionProviders = [];

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
        foreach ($this->optionProviders as $providerKey => $optionProvider) {
            if (!($optionProvider instanceof OptionProviderInterface)) {
                $this->optionProviders[$providerKey] = Instance::ensure($optionProvider, OptionProviderInterface::class);
                $optionProvider = $this->optionProviders[$providerKey];
            }
            if ($optionProvider->hasType($type)) {
                return $optionProvider->getOptions($type, $key);
            }
        }
        throw new NotFoundHttpException("Option {$type} not found");
    }
}
