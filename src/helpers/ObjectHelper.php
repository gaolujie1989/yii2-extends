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
     * @param string $class
     * @param $config
     * @param null $data
     * @return object
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public static function create(string $class, $config, $data = null)
    {
        if (is_string($config)) {
            $config = ['class' => $config];
        }
        if ($data !== null) {
            foreach ($config as $key => $path) {
                $config[$key] = ArrayHelper::getValue($data, $path);
            }
        }
        if (empty($config['class'])) {
            $config['class'] = $class;
        }
        return Yii::createObject($config);
    }
}
