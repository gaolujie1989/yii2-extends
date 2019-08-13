<?php

namespace dpd\Type;

use Phpro\SoapClient\Type\RequestInterface;

class ProactiveNotification implements RequestInterface
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
     * Constructor
     *
     * @var int $channel
     * @var string $value
     * @var int $rule
     * @var string $language
     */
    public function __construct($channel, $value, $rule, $language)
    {
        $this->channel = $channel;
        $this->value = $value;
        $this->rule = $rule;
        $this->language = $language;
    }

    /**
     * @return int
     */
    public function getChannel()
    {
        return $this->channel;
    }

    /**
     * @param int $channel
     * @return ProactiveNotification
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
     * @return ProactiveNotification
     */
    public function withValue($value)
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
     * @return ProactiveNotification
     */
    public function withRule($rule)
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
     * @return ProactiveNotification
     */
    public function withLanguage($language)
    {
        $new = clone $this;
        $new->language = $language;

        return $new;
    }


}

