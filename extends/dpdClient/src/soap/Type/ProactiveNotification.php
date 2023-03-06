<?php

namespace lujie\dpd\soap\Type;

use yii\base\BaseObject;

class ProactiveNotification extends BaseObject
{

    /**
     * @var int
     */
    private $channel;

    /**
     * @var string
     */
    private $value;

    /**
     * @var int
     */
    private $rule;

    /**
     * @var string
     */
    private $language;

    /**
     * @return int
     */
    public function getChannel()
    {
        return $this->channel;
    }

    /**
     * @param int $channel
     * @return $this
     */
    public function setChannel(int $channel) : \lujie\dpd\soap\Type\ProactiveNotification
    {
        $this->channel = $channel;
        return $this;
    }

    /**
     * @param int $channel
     * @return ProactiveNotification
     */
    public function withChannel(int $channel) : \lujie\dpd\soap\Type\ProactiveNotification
    {
        $new = clone $this;
        $new->channel = $channel;

        return $new;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setValue(string $value) : \lujie\dpd\soap\Type\ProactiveNotification
    {
        $this->value = $value;
        return $this;
    }

    /**
     * @param string $value
     * @return ProactiveNotification
     */
    public function withValue(string $value) : \lujie\dpd\soap\Type\ProactiveNotification
    {
        $new = clone $this;
        $new->value = $value;

        return $new;
    }

    /**
     * @return int
     */
    public function getRule()
    {
        return $this->rule;
    }

    /**
     * @param int $rule
     * @return $this
     */
    public function setRule(int $rule) : \lujie\dpd\soap\Type\ProactiveNotification
    {
        $this->rule = $rule;
        return $this;
    }

    /**
     * @param int $rule
     * @return ProactiveNotification
     */
    public function withRule(int $rule) : \lujie\dpd\soap\Type\ProactiveNotification
    {
        $new = clone $this;
        $new->rule = $rule;

        return $new;
    }

    /**
     * @return string
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * @param string $language
     * @return $this
     */
    public function setLanguage(string $language) : \lujie\dpd\soap\Type\ProactiveNotification
    {
        $this->language = $language;
        return $this;
    }

    /**
     * @param string $language
     * @return ProactiveNotification
     */
    public function withLanguage(string $language) : \lujie\dpd\soap\Type\ProactiveNotification
    {
        $new = clone $this;
        $new->language = $language;

        return $new;
    }


}

