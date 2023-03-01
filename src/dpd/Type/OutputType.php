<?php

namespace dpd\Type;

class OutputType
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


}

