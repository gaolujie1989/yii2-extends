<?php

namespace dpd\Type;

use Phpro\SoapClient\Type\RequestInterface;

class StatusInfo implements RequestInterface
{

    /**
     * @var string
     */
    private $status;

    /**
     * @var \dpd\Type\ContentLine
     */
    private $label;

    /**
     * @var \dpd\Type\ContentItem
     */
    private $description;

    /**
     * @var bool
     */
    private $statusHasBeenReached;

    /**
     * @var bool
     */
    private $isCurrentStatus;

    /**
     * @var bool
     */
    private $showContactInfo;

    /**
     * @var \dpd\Type\ContentLine
     */
    private $location;

    /**
     * @var \dpd\Type\ContentLine
     */
    private $date;

    /**
     * @var \dpd\Type\ContentItem
     */
    private $normalItems;

    /**
     * @var \dpd\Type\ContentItem
     */
    private $importantItems;

    /**
     * @var \dpd\Type\ContentItem
     */
    private $errorItems;

    /**
     * Constructor
     *
     * @var string $status
     * @var \dpd\Type\ContentLine $label
     * @var \dpd\Type\ContentItem $description
     * @var bool $statusHasBeenReached
     * @var bool $isCurrentStatus
     * @var bool $showContactInfo
     * @var \dpd\Type\ContentLine $location
     * @var \dpd\Type\ContentLine $date
     * @var \dpd\Type\ContentItem $normalItems
     * @var \dpd\Type\ContentItem $importantItems
     * @var \dpd\Type\ContentItem $errorItems
     */
    public function __construct($status, $label, $description, $statusHasBeenReached, $isCurrentStatus, $showContactInfo, $location, $date, $normalItems, $importantItems, $errorItems)
    {
        $this->status = $status;
        $this->label = $label;
        $this->description = $description;
        $this->statusHasBeenReached = $statusHasBeenReached;
        $this->isCurrentStatus = $isCurrentStatus;
        $this->showContactInfo = $showContactInfo;
        $this->location = $location;
        $this->date = $date;
        $this->normalItems = $normalItems;
        $this->importantItems = $importantItems;
        $this->errorItems = $errorItems;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string $status
     * @return StatusInfo
     */
    public function withStatus($status)
    {
        $new = clone $this;
        $new->status = $status;

        return $new;
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
     * @return StatusInfo
     */
    public function withLabel($label)
    {
        $new = clone $this;
        $new->label = $label;

        return $new;
    }

    /**
     * @return \dpd\Type\ContentItem
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param \dpd\Type\ContentItem $description
     * @return StatusInfo
     */
    public function withDescription($description)
    {
        $new = clone $this;
        $new->description = $description;

        return $new;
    }

    /**
     * @return bool
     */
    public function getStatusHasBeenReached()
    {
        return $this->statusHasBeenReached;
    }

    /**
     * @param bool $statusHasBeenReached
     * @return StatusInfo
     */
    public function withStatusHasBeenReached($statusHasBeenReached)
    {
        $new = clone $this;
        $new->statusHasBeenReached = $statusHasBeenReached;

        return $new;
    }

    /**
     * @return bool
     */
    public function getIsCurrentStatus()
    {
        return $this->isCurrentStatus;
    }

    /**
     * @param bool $isCurrentStatus
     * @return StatusInfo
     */
    public function withIsCurrentStatus($isCurrentStatus)
    {
        $new = clone $this;
        $new->isCurrentStatus = $isCurrentStatus;

        return $new;
    }

    /**
     * @return bool
     */
    public function getShowContactInfo()
    {
        return $this->showContactInfo;
    }

    /**
     * @param bool $showContactInfo
     * @return StatusInfo
     */
    public function withShowContactInfo($showContactInfo)
    {
        $new = clone $this;
        $new->showContactInfo = $showContactInfo;

        return $new;
    }

    /**
     * @return \dpd\Type\ContentLine
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @param \dpd\Type\ContentLine $location
     * @return StatusInfo
     */
    public function withLocation($location)
    {
        $new = clone $this;
        $new->location = $location;

        return $new;
    }

    /**
     * @return \dpd\Type\ContentLine
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param \dpd\Type\ContentLine $date
     * @return StatusInfo
     */
    public function withDate($date)
    {
        $new = clone $this;
        $new->date = $date;

        return $new;
    }

    /**
     * @return \dpd\Type\ContentItem
     */
    public function getNormalItems()
    {
        return $this->normalItems;
    }

    /**
     * @param \dpd\Type\ContentItem $normalItems
     * @return StatusInfo
     */
    public function withNormalItems($normalItems)
    {
        $new = clone $this;
        $new->normalItems = $normalItems;

        return $new;
    }

    /**
     * @return \dpd\Type\ContentItem
     */
    public function getImportantItems()
    {
        return $this->importantItems;
    }

    /**
     * @param \dpd\Type\ContentItem $importantItems
     * @return StatusInfo
     */
    public function withImportantItems($importantItems)
    {
        $new = clone $this;
        $new->importantItems = $importantItems;

        return $new;
    }

    /**
     * @return \dpd\Type\ContentItem
     */
    public function getErrorItems()
    {
        return $this->errorItems;
    }

    /**
     * @param \dpd\Type\ContentItem $errorItems
     * @return StatusInfo
     */
    public function withErrorItems($errorItems)
    {
        $new = clone $this;
        $new->errorItems = $errorItems;

        return $new;
    }


}

