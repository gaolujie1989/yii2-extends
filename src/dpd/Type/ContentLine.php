<?php

namespace dpd\Type;

use Phpro\SoapClient\Type\RequestInterface;

class ContentLine implements RequestInterface
{

    /**
     * @var string
     */
    private $content;

    /**
     * @var bool
     */
    private $bold;

    /**
     * @var bool
     */
    private $paragraph;

    /**
     * Constructor
     *
     * @var string $content
     * @var bool $bold
     * @var bool $paragraph
     */
    public function __construct($content, $bold, $paragraph)
    {
        $this->content = $content;
        $this->bold = $bold;
        $this->paragraph = $paragraph;
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
     * @return ContentLine
     */
    public function withContent($content)
    {
        $new = clone $this;
        $new->content = $content;

        return $new;
    }

    /**
     * @return bool
     */
    public function getBold()
    {
        return $this->bold;
    }

    /**
     * @param bool $bold
     * @return ContentLine
     */
    public function withBold($bold)
    {
        $new = clone $this;
        $new->bold = $bold;

        return $new;
    }

    /**
     * @return bool
     */
    public function getParagraph()
    {
        return $this->paragraph;
    }

    /**
     * @param bool $paragraph
     * @return ContentLine
     */
    public function withParagraph($paragraph)
    {
        $new = clone $this;
        $new->paragraph = $paragraph;

        return $new;
    }


}

