<?php

namespace lujie\dpd\soap\Type;

use yii\base\BaseObject;

class PrintOptions extends BaseObject
{
    /**
     * @var \lujie\dpd\soap\Type\PrintOption
     */
    private $printOption;

    /**
     * @var bool
     */
    private $splitByParcel;

    /**
     * @return \lujie\dpd\soap\Type\PrintOption
     */
    public function getPrintOption()
    {
        return $this->printOption;
    }

    /**
     * @param \lujie\dpd\soap\Type\PrintOption $printOption
     * @return PrintOptions
     */
    public function withPrintOption($printOption)
    {
        $new = clone $this;
        $new->printOption = $printOption;

        return $new;
    }

    /**
     * @param \lujie\dpd\soap\Type\PrintOption $printOption
     * @return $this
     */
    public function setPrintOption($printOption) : \lujie\dpd\soap\Type\PrintOptions
    {
        $this->printOption = $printOption;
        return $this;
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

    /**
     * @param bool $splitByParcel
     * @return $this
     */
    public function setSplitByParcel(bool $splitByParcel) : \lujie\dpd\soap\Type\PrintOptions
    {
        $this->splitByParcel = $splitByParcel;
        return $this;
    }
}

