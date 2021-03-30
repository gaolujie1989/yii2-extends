<?php

namespace dpd\Type;

use Phpro\SoapClient\Type\RequestInterface;

class PrintOptions implements RequestInterface
{

    /**
     * @var string
     */
    private $printerLanguage;

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
     * Constructor
     *
     * @var string $printerLanguage
     * @var string $paperFormat
     * @var \dpd\Type\Printer $printer
     * @var string $startPosition
     */
    public function __construct($printerLanguage, $paperFormat, $printer, $startPosition)
    {
        $this->printerLanguage = $printerLanguage;
        $this->paperFormat = $paperFormat;
        $this->printer = $printer;
        $this->startPosition = $startPosition;
    }

    /**
     * @return string
     */
    public function getPrinterLanguage()
    {
        return $this->printerLanguage;
    }

    /**
     * @param string $printerLanguage
     * @return PrintOptions
     */
    public function withPrinterLanguage($printerLanguage)
    {
        $new = clone $this;
        $new->printerLanguage = $printerLanguage;

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
     * @return PrintOptions
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
     * @return PrintOptions
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
     * @return PrintOptions
     */
    public function withStartPosition($startPosition)
    {
        $new = clone $this;
        $new->startPosition = $startPosition;

        return $new;
    }
}
