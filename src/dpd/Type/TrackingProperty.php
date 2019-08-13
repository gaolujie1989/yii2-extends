<?php

namespace dpd\Type;

use Phpro\SoapClient\Type\RequestInterface;

class TrackingProperty implements RequestInterface
{

    /**
     * @var string
     */
    private $key;

    /**
     * @var string
     */
    private $value;

    /**
     * Constructor
     *
     * @var string $key
     * @var string $value
     */
    public function __construct($key, $value)
    {
        $this->key = $key;
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param string $key
     * @return TrackingProperty
     */
    public function withKey($key)
    {
        $new = clone $this;
        $new->key = $key;

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
     * @return TrackingProperty
     */
    public function withValue($value)
    {
        $new = clone $this;
        $new->value = $value;

        return $new;
    }


}

