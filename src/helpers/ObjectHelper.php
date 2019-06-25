<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\helpers;

use yii\di\Instance;

class ObjectHelper
{
    /**
     * @param $config
     * @param $class
     * @return object
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public static function create($config, $class): object
    {
        if (is_string($config)) {
            $config = ['class' => $config];
        }
        if (empty($config['class'])) {
            $config['class'] = $class;
        }
        return Instance::ensure($config, $class);
    }
}
