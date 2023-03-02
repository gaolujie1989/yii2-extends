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
    public function getFormat() : string
    {
        return $this->format;
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
     * @param string $format
     * @return OutputType
     */
    public function withFormat(string $format) : \lujie\dpd\soap\Type\OutputType
    {
        $new = clone $this;
        $new->format = $format;

        return $new;
    }

    /**
     * @return string
     */
    public function getContent() : string
    {
        return $this->content;
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

    /**
     * @param string $content
     * @return OutputType
     */
    public function withContent(string $content) : \lujie\dpd\soap\Type\OutputType
    {
        $new = clone $this;
        $new->content = $content;

        return $new;
    }


}

