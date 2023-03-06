<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\helpers;

use Yii;
use yii\base\InvalidArgumentException;
use yii\base\Module;
use yii\helpers\StringHelper;

/**
 * Class ComponentHelper
 * @package extensions\core\helpers
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ComponentHelper
{
    /**
     * @param object $component
     * @param Module|null $app
     * @return string
     * @inheritdoc
     */
    public static function getName(object $component, ?Module $app = null): string
    {
        $app = $app ?: Yii::$app;
        foreach ($app->getComponents(false) as $id => $instance) {
            if ($instance === $component) {
                return $id;
            }
        }
        throw new InvalidArgumentException('Instance must be an application component.');
    }

    /**
     * @inheritdoc
     */
    public static function closeConnections(): void
    {
        $app = Yii::$app;
        foreach ($app->getComponents(false) as $id => $instance) {
            if (method_exists($instance, 'close') && ClassHelper::getClassShortName($instance) === 'Connection') {
                $instance->close();
            }
        }
    }
}
