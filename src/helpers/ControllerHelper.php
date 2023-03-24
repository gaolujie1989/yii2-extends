<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\helpers;

use yii\base\Application;
use yii\base\Controller;
use yii\base\Module;
use yii\helpers\Inflector;

/**
 * Class ControllerHelper
 * @package lujie\extend\helpers
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ControllerHelper
{
    /**
     * @param Module $module
     * @return array
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public static function getControllerActions(Module $module): array
    {
        $controllerActions = [];
        $controllers = static::getControllers($module);
        foreach ($controllers as $controller) {
            $result = $module->createController($controller);
            if ($result === false || !is_array($result)) {
                continue;
            }
            [$controller] = $result;
            $actions = static::getActions($controller);
            foreach ($actions as $action) {
                $controllerActions[] = $controller->getUniqueId() . '/' . $action;
            }
        }
        return $controllerActions;
    }

    /**
     * @param Module $module
     * @return array
     * @inheritdoc
     */
    public static function getControllers(Module $module, bool $recursive = true): array
    {
        $prefix = $module instanceof Application ? '' : $module->getUniqueId() . '/';

        $controllers = [];

        foreach (array_keys($module->controllerMap) as $id) {
            $controllers[] = $prefix . $id;
        }

        if ($recursive) {
            foreach ($module->getModules() as $id => $child) {
                if (($child = $module->getModule($id)) === null) {
                    continue;
                }
                foreach (static::getControllers($child) as $command) {
                    $controllers[] = $command;
                }
            }
        }

        $controllerPath = $module->getControllerPath();
        if (is_dir($controllerPath)) {
            $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($controllerPath, \FilesystemIterator::KEY_AS_PATHNAME));
            $iterator = new \RegexIterator($iterator, '/.*Controller\.php$/', \RegexIterator::GET_MATCH);
            foreach ($iterator as $matches) {
                $file = $matches[0];
                $relativePath = str_replace($controllerPath, '', $file);
                $class = strtr($relativePath, [
                    '/' => '\\',
                    '.php' => '',
                ]);
                $controllerClass = $module->controllerNamespace . $class;
                if (static::validateControllerClass($controllerClass)) {
                    $dir = ltrim(pathinfo($relativePath, PATHINFO_DIRNAME), '\\/');

                    $controller = Inflector::camel2id(substr(basename($file), 0, -14), '-', true);
                    if (!empty($dir)) {
                        $controller = $dir . '/' . $controller;
                    }
                    $controllers[] = $prefix . $controller;
                }
            }
        }

        sort($controllers);
        return array_unique($controllers);
    }

    public static function getActions(Controller $controller): array
    {
        $actions = array_keys($controller->actions());
        $class = new \ReflectionClass($controller);
        foreach ($class->getMethods() as $method) {
            $name = $method->getName();
            if ($name !== 'actions' && $method->isPublic() && !$method->isStatic() && strncmp($name, 'action', 6) === 0) {
                $actions[] = Inflector::camel2id(substr($name, 6));
            }
        }
        sort($actions);
        return array_unique($actions);
    }

    /**
     * @param $controllerClass
     * @param $baseControllerClass
     * @return bool
     * @inheritdoc
     */
    public static function validateControllerClass($controllerClass, string $baseControllerClass = Controller::class): bool
    {
        if (class_exists($controllerClass)) {
            $class = new \ReflectionClass($controllerClass);
            return !$class->isAbstract() && $class->isSubclassOf($baseControllerClass);
        }
        return false;
    }
}