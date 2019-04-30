<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\helpers;

use Yii;
use yii\base\InvalidArgumentException;

/**
 * Class ComponentHelper
 * @package extensions\core\helpers
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ComponentHelper
{
    /**
     * @param $component
     * @param null $app
     * @return int|string
     * @throws InvalidArgumentException
     * @inheritdoc
     */
    public static function getName($component, $app = null)
    {
        $app = $app ?: Yii::$app;
        foreach ($app->getComponents(false) as $id => $instance) {
            if ($instance === $component) {
                return $id;
            }
        }
        throw new InvalidArgumentException('Instance must be an application component.');
    }
}
