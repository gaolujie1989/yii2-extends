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
    public $jsonOption = JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE;

    /**
     * @param string $name
     * @return mixed|string
     * @inheritdoc
     */
    public function getAliasProperty(string $name)
    {
        $value = parent::getAliasProperty($name);
        if (is_string($value)) {
            return $value;
        }
        return $value ? Json::encode($value, $this->jsonOption) : '';
    }

    /**
     * @param string $name
     * @param mixed $value
     * @inheritdoc
     */
    public function setAliasProperty(string $name, $value): void
    {
        if (is_string($value)) {
            $value = $value ? Json::decode($value) : [];
        }
        parent::setAliasProperty($name, $value);
    }
}
