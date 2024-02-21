<?php

namespace lujie\dpd\soap\Type;

use yii\base\BaseObject;

class OutputType extends BaseObject
{
    /**
     * @var string
     */
    private $format;

    /**
     * @var string
     */
    private $content;

    /**
     * @return string
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * @param string $format
     * @return OutputType
     */
    public function withFormat($format)
    {
        $new = clone $this;
        $new->format = $format;

        return $new;
    }

    /**
     * @param string $format
     * @return $this
     */
    public function setFormat(string $format) : \lujie\dpd\soap\Type\OutputType
    {
        $this->format = $format;
        return $this;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param string $content
     * @return OutputType
     */
    public function withContent($content)
    {
        $new = clone $this;
        $new->content = $content;

        return $new;
    }

    /**
     * @param string $content
     * @return $this
     */
    public function setContent(string $content) : \lujie\dpd\soap\Type\OutputType
    {
        $this->content = $content;
        return $this;
    }
}

