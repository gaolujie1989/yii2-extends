<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\helpers;

use Yii;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;

/**
 * Class ObjectHelper
 * @package lujie\extend\helpers
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ObjectHelper
{
    /**
     * @param string|array $config
     * @param null|array|object $data
     * @param string|null $class
     * @return object
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public static function create($config, $data, ?string $class = null, $prefix = ':')
    {
        if (is_string($config)) {
            return Yii::createObject($config);
        }

        foreach ($config as $key => $path) {
            if ($key === 'class') {
                continue;
            }
            if (is_string($path) && strpos($path, $prefix) === 0) {
                $config[$key] = ArrayHelper::getValue($data, $path);
            }
        }

        if ($class && empty($config['class'])) {
            $config['class'] = $class;
        }
        return Yii::createObject($config);
    }
}
