<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\common\option;

use lujie\common\option\providers\OptionProvider;
use lujie\common\option\providers\OptionProviderInterface;
use lujie\common\option\providers\QueryOptionProvider;
use PhpOffice\PhpSpreadsheet\Shared\StringHelper;
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
     * @param string|null $key
     * @param array|null $values
     * @return array
     * @throws NotFoundHttpException
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function getOptions(string $type, ?string $key = null, ?array $values = null, ?array $params = null): array
    {
        if (isset($this->providers[$type])) {
            $optionProvider = $this->getOptionProvider($this->providers[$type], $type);
            if ($optionProvider->hasType($type)) {
                return $optionProvider->getOptions($type, $key, $values, $params);
            }
        }
        foreach ($this->providers as $providerKey => $optionProvider) {
            $optionProvider = $this->getOptionProvider($optionProvider, $providerKey);
            if ($optionProvider->hasType($type)) {
                return $optionProvider->getOptions($type, $key, $values, $params);
            }
        }
        throw new NotFoundHttpException("Option {$type} not found");
    }

    /**
     * @param $optionProvider
     * @param string $providerKey
     * @return OptionProviderInterface
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    protected function getOptionProvider($optionProvider, string $providerKey): OptionProviderInterface
    {
        if (!($optionProvider instanceof OptionProviderInterface)) {
            $optionProvider = Instance::ensure($optionProvider, OptionProviderInterface::class);
            $this->providers[$providerKey] = $optionProvider;
            if ($optionProvider instanceof QueryOptionProvider && empty($optionProvider->type)) {
                $optionProvider->type = $providerKey;
            }
        }
        return $optionProvider;
    }
}
