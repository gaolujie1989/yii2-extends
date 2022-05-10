<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\common\option;

use lujie\common\option\providers\OptionProviderInterface;
use yii\base\BaseObject;
use yii\di\Instance;
use yii\web\NotFoundHttpException;

/**
 * Class OptionManager
 * @package lujie\common\option
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class OptionManager extends BaseObject
{
    /**
     * @var OptionProviderInterface[]
     */
    public $providers = [];

    /**
     * @param string $type
     * @param string $key
     * @param bool|string|null $like
     * @return array
     * @throws NotFoundHttpException
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function getOptions(string $type, string $key = '', $like = null): array
    {
        foreach ($this->providers as $providerKey => $optionProvider) {
            if (!($optionProvider instanceof OptionProviderInterface)) {
                $this->providers[$providerKey] = Instance::ensure($optionProvider, OptionProviderInterface::class);
                $optionProvider = $this->providers[$providerKey];
            }
            if ($optionProvider->hasType($type)) {
                return $like !== null
                    ? $optionProvider->getOptions($type, $key, $like)
                    : $optionProvider->getOptions($type, $key);
            }
        }
        throw new NotFoundHttpException("Option {$type} not found");
    }
}