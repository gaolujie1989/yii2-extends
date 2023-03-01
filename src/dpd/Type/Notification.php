<?php

namespace dpd\Type;

class Notification
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
    public function getChannel()
    {
        return $this->channel;
    }

    /**
     * @param int $channel
     * @return Notification
     */
    public function withChannel($channel)
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
     * @return Notification
     */
    public function withValue($value)
    {
        $new = clone $this;
        $new->value = $value;

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
     * @return Notification
     */
    public function withLanguage($language)
    {
        $new = clone $this;
        $new->language = $language;

        return $new;
    }


}

