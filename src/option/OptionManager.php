<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\common\option;

use lujie\common\option\providers\OptionProvider;
use lujie\common\option\providers\OptionProviderInterface;
use lujie\common\option\providers\QueryOptionProvider;
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
     * @var array
     */
    public $defaultProviders = [
        'option' => [
            'class' => OptionProvider::class
        ]
    ];

    /**
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        $this->providers = array_merge($this->defaultProviders, $this->providers);
    }

    /**
     * @param string $type
     * @param string $key
     * @param null $like
     * @return array
     * @throws NotFoundHttpException
     * @throws \yii\base\InvalidConfigException
     * @throws \Exception
     * @inheritdoc
     */
    public function getOptions(string $type, string $key = '', $like = null): array
    {
        foreach ($this->providers as $providerKey => $optionProvider) {
            if (!($optionProvider instanceof OptionProviderInterface)) {
                $optionProvider = Instance::ensure($optionProvider, OptionProviderInterface::class);
                $this->providers[$providerKey] = $optionProvider;
                if ($optionProvider instanceof QueryOptionProvider && empty($optionProvider->type)) {
                    $optionProvider->type = $providerKey;
                }
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