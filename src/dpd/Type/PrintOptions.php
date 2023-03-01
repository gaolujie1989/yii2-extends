<?php

namespace dpd\Type;

class PrintOptions
{

    /**
     * @var \dpd\Type\PrintOption
     */
    private $printOption;

    /**
     * @var bool
     */
    private $splitByParcel;

    /**
     * @return \dpd\Type\PrintOption
     */
    public function getPrintOption()
    {
        return $this->printOption;
    }

    /**
     * @param \dpd\Type\PrintOption $printOption
     * @return PrintOptions
     */
    public function withPrintOption($printOption)
    {
        $new = clone $this;
        $new->printOption = $printOption;

        return $new;
    }

    /**
     * @return bool
     */
    public function getSplitByParcel()
    {
        return $this->splitByParcel;
    }

    /**
     * @param bool $splitByParcel
     * @return PrintOptions
     */
    public function withSplitByParcel($splitByParcel)
    {
        $new = clone $this;
        $new->splitByParcel = $splitByParcel;

        return $new;
    }


}

