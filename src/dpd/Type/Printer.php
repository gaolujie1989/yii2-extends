<?php

namespace dpd\Type;

use Phpro\SoapClient\Type\RequestInterface;

class Printer implements RequestInterface
{

    /**
     * @var string
     */
    private $manufacturer;

    /**
     * @var string
     */
    private $model;

    /**
     * @var string
     */
    private $revision;

    /**
     * @var float
     */
    private $offsetX;

    /**
     * @var float
     */
    private $offsetY;

    /**
     * @var string
     */
    private $connectionType;

    /**
     * @var bool
     */
    private $barcodeCapable2D;

    /**
     * Constructor
     *
     * @var string $manufacturer
     * @var string $model
     * @var string $revision
     * @var float $offsetX
     * @var float $offsetY
     * @var string $connectionType
     * @var bool $barcodeCapable2D
     */
    public function __construct($manufacturer, $model, $revision, $offsetX, $offsetY, $connectionType, $barcodeCapable2D)
    {
        $this->manufacturer = $manufacturer;
        $this->model = $model;
        $this->revision = $revision;
        $this->offsetX = $offsetX;
        $this->offsetY = $offsetY;
        $this->connectionType = $connectionType;
        $this->barcodeCapable2D = $barcodeCapable2D;
    }

    /**
     * @return string
     */
    public function getManufacturer()
    {
        return $this->manufacturer;
    }

    /**
     * @param string $manufacturer
     * @return Printer
     */
    public function withManufacturer($manufacturer)
    {
        $new = clone $this;
        $new->manufacturer = $manufacturer;

        return $new;
    }

    /**
     * @return string
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @param string $model
     * @return Printer
     */
    public function withModel($model)
    {
        $new = clone $this;
        $new->model = $model;

        return $new;
    }

    /**
     * @return string
     */
    public function getRevision()
    {
        return $this->revision;
    }

    /**
     * @param string $revision
     * @return Printer
     */
    public function withRevision($revision)
    {
        $new = clone $this;
        $new->revision = $revision;

        return $new;
    }

    /**
     * @return float
     */
    public function getOffsetX()
    {
        return $this->offsetX;
    }

    /**
     * @param float $offsetX
     * @return Printer
     */
    public function withOffsetX($offsetX)
    {
        $new = clone $this;
        $new->offsetX = $offsetX;

        return $new;
    }

    /**
     * @return float
     */
    public function getOffsetY()
    {
        return $this->offsetY;
    }

    /**
     * @param float $offsetY
     * @return Printer
     */
    public function withOffsetY($offsetY)
    {
        $new = clone $this;
        $new->offsetY = $offsetY;

        return $new;
    }

    /**
     * @return string
     */
    public function getConnectionType()
    {
        return $this->connectionType;
    }

    /**
     * @param string $connectionType
     * @return Printer
     */
    public function withConnectionType($connectionType)
    {
        $new = clone $this;
        $new->connectionType = $connectionType;

        return $new;
    }

    /**
     * @return bool
     */
    public function getBarcodeCapable2D()
    {
        return $this->barcodeCapable2D;
    }

    /**
     * @param bool $barcodeCapable2D
     * @return Printer
     */
    public function withBarcodeCapable2D($barcodeCapable2D)
    {
        $new = clone $this;
        $new->barcodeCapable2D = $barcodeCapable2D;

        return $new;
    }


}

