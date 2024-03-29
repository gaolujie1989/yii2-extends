<?php

namespace lujie\dpd\soap\Type;

use yii\base\BaseObject;

class PrintOption extends BaseObject
{
    /**
     * @var string
     */
    private $outputFormat;

    /**
     * @var string
     */
    private $paperFormat;

    /**
     * @var \lujie\dpd\soap\Type\Printer
     */
    private $printer;

    /**
     * @var string
     */
    private $startPosition;

    /**
     * @return string
     */
    public function getOutputFormat()
    {
        return $this->outputFormat;
    }

    /**
     * @param string $outputFormat
     * @return PrintOption
     */
    public function withOutputFormat($outputFormat)
    {
        $new = clone $this;
        $new->outputFormat = $outputFormat;

        return $new;
    }

    /**
     * @param string $outputFormat
     * @return $this
     */
    public function setOutputFormat(string $outputFormat) : \lujie\dpd\soap\Type\PrintOption
    {
        $this->outputFormat = $outputFormat;
        return $this;
    }

    /**
     * @return string
     */
    public function getPaperFormat()
    {
        return $this->paperFormat;
    }

    /**
     * @param string $paperFormat
     * @return PrintOption
     */
    public function withPaperFormat($paperFormat)
    {
        $new = clone $this;
        $new->paperFormat = $paperFormat;

        return $new;
    }

    /**
     * @param string $paperFormat
     * @return $this
     */
    public function setPaperFormat(string $paperFormat) : \lujie\dpd\soap\Type\PrintOption
    {
        $this->paperFormat = $paperFormat;
        return $this;
    }

    /**
     * @return \lujie\dpd\soap\Type\Printer
     */
    public function getPrinter()
    {
        return $this->printer;
    }

    /**
     * @param \lujie\dpd\soap\Type\Printer $printer
     * @return PrintOption
     */
    public function withPrinter($printer)
    {
        $new = clone $this;
        $new->printer = $printer;

        return $new;
    }

    /**
     * @param \lujie\dpd\soap\Type\Printer $printer
     * @return $this
     */
    public function setPrinter($printer) : \lujie\dpd\soap\Type\PrintOption
    {
        $this->printer = $printer;
        return $this;
    }

    /**
     * @return string
     */
    public function getStartPosition()
    {
        return $this->startPosition;
    }

    /**
     * @param string $startPosition
     * @return PrintOption
     */
    public function withStartPosition($startPosition)
    {
        $new = clone $this;
        $new->startPosition = $startPosition;

        return $new;
    }

    /**
     * @param string $startPosition
     * @return $this
     */
    public function setStartPosition(string $startPosition) : \lujie\dpd\soap\Type\PrintOption
    {
        $this->startPosition = $startPosition;
        return $this;
    }
}

