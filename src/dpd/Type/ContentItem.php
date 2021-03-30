<?php

namespace dpd\Type;

use Phpro\SoapClient\Type\RequestInterface;

class ContentItem implements RequestInterface
{

    /**
     * @var \dpd\Type\ContentLine
     */
    private $label;

    /**
     * @var \dpd\Type\ContentLine
     */
    private $content;

    /**
     * @var string
     */
    private $linkTarget;

    /**
     * Constructor
     *
     * @var \dpd\Type\ContentLine $label
     * @var \dpd\Type\ContentLine $content
     * @var string $linkTarget
     */
    public function __construct($label, $content, $linkTarget)
    {
        $this->label = $label;
        $this->content = $content;
        $this->linkTarget = $linkTarget;
    }

    /**
     * @return \dpd\Type\ContentLine
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param \dpd\Type\ContentLine $label
     * @return ContentItem
     */
    public function withLabel($label)
    {
        $new = clone $this;
        $new->label = $label;

        return $new;
    }

    /**
     * @return \dpd\Type\ContentLine
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param \dpd\Type\ContentLine $content
     * @return ContentItem
     */
    public function withContent($content)
    {
        $new = clone $this;
        $new->content = $content;

        return $new;
    }

    /**
     * @return string
     */
    public function getLinkTarget()
    {
        return $this->linkTarget;
    }

    /**
     * @param string $linkTarget
     * @return ContentItem
     */
    public function withLinkTarget($linkTarget)
    {
        $new = clone $this;
        $new->linkTarget = $linkTarget;

        return $new;
    }
}
