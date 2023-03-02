<?php

namespace lujie\dpd\soap\Type;

use yii\base\BaseObject;

class Notification extends BaseObject
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
     * @var string
     */
    private $language;

    /**
     * @return int
     */
    public function getChannel() : int
    {
        return $this->channel;
    }

    /**
     * @param int $channel
     * @return $this
     */
    public function setChannel(int $channel) : \lujie\dpd\soap\Type\Notification
    {
        $this->channel = $channel;
        return $this;
    }

    /**
     * @param int $channel
     * @return Notification
     */
    public function withChannel(int $channel) : \lujie\dpd\soap\Type\Notification
    {
        $new = clone $this;
        $new->channel = $channel;

        return $new;
    }

    /**
     * @return string
     */
    public function getValue() : string
    {
        return $this->value;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setValue(string $value) : \lujie\dpd\soap\Type\Notification
    {
        $this->value = $value;
        return $this;
    }

    /**
     * @param string $value
     * @return Notification
     */
    public function withValue(string $value) : \lujie\dpd\soap\Type\Notification
    {
        $new = clone $this;
        $new->value = $value;

        return $new;
    }

    /**
     * @return string
     */
    public function getLanguage() : string
    {
        return $this->language;
    }

    /**
     * @param string $language
     * @return $this
     */
    public function setLanguage(string $language) : \lujie\dpd\soap\Type\Notification
    {
        $this->language = $language;
        return $this;
    }

    /**
     * @param string $language
     * @return Notification
     */
    public function withLanguage(string $language) : \lujie\dpd\soap\Type\Notification
    {
        $new = clone $this;
        $new->language = $language;

        return $new;
    }


}

