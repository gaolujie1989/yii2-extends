<?php
/**
 * @copyright Copyright (c) 2017
 */

namespace lujie\alias\behaviors;


use yii\helpers\Json;

/**
 * Class TimestampAliasBehavior
 * @package lujie\core\behaviors
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class JsonAliasBehavior extends AliasPropertyBehavior
{
    /**
     * @param $name
     * @return int|mixed|string
     * @throws \Exception
     * @inheritdoc
     */
    public function getAliasProperty($name)
    {
        $value = parent::getAliasProperty($name);
        return is_string($value) ? $value : Json::encode($value);
    }

    /**
     * @param $name
     * @param $value
     * @throws \Exception
     * @inheritdoc
     */
    public function setAliasProperty($name, $value): void
    {
        $value = is_array($value) ? $value : Json::decode($value);
        parent::setAliasProperty($name, $value);
    }
}
