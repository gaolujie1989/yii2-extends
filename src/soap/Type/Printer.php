<?php

namespace lujie\dpd\soap\Type;

use yii\base\BaseObject;

class Printer extends BaseObject
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
     * @return string
     */
    public function getManufacturer()
    {
        return $this->manufacturer;
    }

    /**
     * @param string $manufacturer
     * @return $this
     */
    public function setManufacturer(string $manufacturer) : \lujie\dpd\soap\Type\Printer
    {
        $this->manufacturer = $manufacturer;
        return $this;
    }

    /**
     * @param string $manufacturer
     * @return Printer
     */
    public function withManufacturer(string $manufacturer) : \lujie\dpd\soap\Type\Printer
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
     * @return $this
     */
    public function setModel(string $model) : \lujie\dpd\soap\Type\Printer
    {
        $this->model = $model;
        return $this;
    }

    /**
     * @param string $model
     * @return Printer
     */
    public function withModel(string $model) : \lujie\dpd\soap\Type\Printer
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
     * @return $this
     */
    public function setRevision(string $revision) : \lujie\dpd\soap\Type\Printer
    {
        $this->revision = $revision;
        return $this;
    }

    /**
     * @param string $revision
     * @return Printer
     */
    public function withRevision(string $revision) : \lujie\dpd\soap\Type\Printer
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
     * @return $this
     */
    public function setOffsetX(float $offsetX) : \lujie\dpd\soap\Type\Printer
    {
        $this->offsetX = $offsetX;
        return $this;
    }

    /**
     * @param float $offsetX
     * @return Printer
     */
    public function withOffsetX(float $offsetX) : \lujie\dpd\soap\Type\Printer
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
     * @return $this
     */
    public function setOffsetY(float $offsetY) : \lujie\dpd\soap\Type\Printer
    {
        $this->offsetY = $offsetY;
        return $this;
    }

    /**
     * @param float $offsetY
     * @return Printer
     */
    public function withOffsetY(float $offsetY) : \lujie\dpd\soap\Type\Printer
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
     * @return $this
     */
    public function setConnectionType(string $connectionType) : \lujie\dpd\soap\Type\Printer
    {
        $this->connectionType = $connectionType;
        return $this;
    }

    /**
     * @param string $connectionType
     * @return Printer
     */
    public function withConnectionType(string $connectionType) : \lujie\dpd\soap\Type\Printer
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
     * @return $this
     */
    public function setBarcodeCapable2D(bool $barcodeCapable2D) : \lujie\dpd\soap\Type\Printer
    {
        $this->barcodeCapable2D = $barcodeCapable2D;
        return $this;
    }

    /**
     * @param bool $barcodeCapable2D
     * @return Printer
     */
    public function withBarcodeCapable2D(bool $barcodeCapable2D) : \lujie\dpd\soap\Type\Printer
    {
        $new = clone $this;
        $new->barcodeCapable2D = $barcodeCapable2D;

        return $new;
    }


}

