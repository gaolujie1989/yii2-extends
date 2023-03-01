<?php

namespace dpd\Type;

class PrintOption
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
     * @var \dpd\Type\Printer
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
     * @return \dpd\Type\Printer
     */
    public function getPrinter()
    {
        return $this->printer;
    }

    /**
     * @param \dpd\Type\Printer $printer
     * @return PrintOption
     */
    public function withPrinter($printer)
    {
        $new = clone $this;
        $new->printer = $printer;

        return $new;
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


}

