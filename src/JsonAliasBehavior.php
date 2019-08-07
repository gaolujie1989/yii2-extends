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
    public $jsonOption = JSON_PRETTY_PRINT;

    /**
     * @param $name
     * @return int|mixed|string
     * @throws \Exception
     * @inheritdoc
     */
    public function getAliasProperty($name)
    {
        $value = parent::getAliasProperty($name);
        if (is_string($value)) {
            return $value;
        }
        return $value ? Json::encode($value, $this->jsonOption) : '';
    }

    /**
     * @param $name
     * @param $value
     * @throws \Exception
     * @inheritdoc
     */
    public function setAliasProperty($name, $value): void
    {
        if (is_string($value)) {
            $value = $value ? Json::decode($value) : [];
        }
        parent::setAliasProperty($name, $value);
    }
}
