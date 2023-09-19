<?php

namespace lujie\dpd\soap\Type;

use yii\base\BaseObject;

class AdditionalInvoiceLine extends BaseObject
{
    /**
     * @var int
     */
    private $customsInvoicePosition;

    /**
     * @var int
     */
    private $quantityItems;

    /**
     * @var string
     */
    private $customsContent;

    /**
     * @var string
     */
    private $customsTarif;

    /**
     * @var int
     */
    private $customsAmountLine;

    /**
     * @var string
     */
    private $customsOrigin;

    /**
     * @var int
     */
    private $customsNetWeight;

    /**
     * @var int
     */
    private $customsGrossWeight;

    /**
     * @var string
     */
    private $productFabricComposition;

    /**
     * @var string
     */
    private $productCode;

    /**
     * @var string
     */
    private $productShortDescription;

    /**
     * @return int
     */
    public function getCustomsInvoicePosition()
    {
        return $this->customsInvoicePosition;
    }

    /**
     * @param int $customsInvoicePosition
     * @return AdditionalInvoiceLine
     */
    public function withCustomsInvoicePosition($customsInvoicePosition)
    {
        $new = clone $this;
        $new->customsInvoicePosition = $customsInvoicePosition;

        return $new;
    }

    /**
     * @param int $customsInvoicePosition
     * @return $this
     */
    public function setCustomsInvoicePosition(int $customsInvoicePosition) : \lujie\dpd\soap\Type\AdditionalInvoiceLine
    {
        $this->customsInvoicePosition = $customsInvoicePosition;
        return $this;
    }

    /**
     * @return int
     */
    public function getQuantityItems()
    {
        return $this->quantityItems;
    }

    /**
     * @param int $quantityItems
     * @return AdditionalInvoiceLine
     */
    public function withQuantityItems($quantityItems)
    {
        $new = clone $this;
        $new->quantityItems = $quantityItems;

        return $new;
    }

    /**
     * @param int $quantityItems
     * @return $this
     */
    public function setQuantityItems(int $quantityItems) : \lujie\dpd\soap\Type\AdditionalInvoiceLine
    {
        $this->quantityItems = $quantityItems;
        return $this;
    }

    /**
     * @return string
     */
    public function getCustomsContent()
    {
        return $this->customsContent;
    }

    /**
     * @param string $customsContent
     * @return AdditionalInvoiceLine
     */
    public function withCustomsContent($customsContent)
    {
        $new = clone $this;
        $new->customsContent = $customsContent;

        return $new;
    }

    /**
     * @param string $customsContent
     * @return $this
     */
    public function setCustomsContent(string $customsContent) : \lujie\dpd\soap\Type\AdditionalInvoiceLine
    {
        $this->customsContent = $customsContent;
        return $this;
    }

    /**
     * @return string
     */
    public function getCustomsTarif()
    {
        return $this->customsTarif;
    }

    /**
     * @param string $customsTarif
     * @return AdditionalInvoiceLine
     */
    public function withCustomsTarif($customsTarif)
    {
        $new = clone $this;
        $new->customsTarif = $customsTarif;

        return $new;
    }

    /**
     * @param string $customsTarif
     * @return $this
     */
    public function setCustomsTarif(string $customsTarif) : \lujie\dpd\soap\Type\AdditionalInvoiceLine
    {
        $this->customsTarif = $customsTarif;
        return $this;
    }

    /**
     * @return int
     */
    public function getCustomsAmountLine()
    {
        return $this->customsAmountLine;
    }

    /**
     * @param int $customsAmountLine
     * @return AdditionalInvoiceLine
     */
    public function withCustomsAmountLine($customsAmountLine)
    {
        $new = clone $this;
        $new->customsAmountLine = $customsAmountLine;

        return $new;
    }

    /**
     * @param int $customsAmountLine
     * @return $this
     */
    public function setCustomsAmountLine(int $customsAmountLine) : \lujie\dpd\soap\Type\AdditionalInvoiceLine
    {
        $this->customsAmountLine = $customsAmountLine;
        return $this;
    }

    /**
     * @return string
     */
    public function getCustomsOrigin()
    {
        return $this->customsOrigin;
    }

    /**
     * @param string $customsOrigin
     * @return AdditionalInvoiceLine
     */
    public function withCustomsOrigin($customsOrigin)
    {
        $new = clone $this;
        $new->customsOrigin = $customsOrigin;

        return $new;
    }

    /**
     * @param string $customsOrigin
     * @return $this
     */
    public function setCustomsOrigin(string $customsOrigin) : \lujie\dpd\soap\Type\AdditionalInvoiceLine
    {
        $this->customsOrigin = $customsOrigin;
        return $this;
    }

    /**
     * @return int
     */
    public function getCustomsNetWeight()
    {
        return $this->customsNetWeight;
    }

    /**
     * @param int $customsNetWeight
     * @return AdditionalInvoiceLine
     */
    public function withCustomsNetWeight($customsNetWeight)
    {
        $new = clone $this;
        $new->customsNetWeight = $customsNetWeight;

        return $new;
    }

    /**
     * @param int $customsNetWeight
     * @return $this
     */
    public function setCustomsNetWeight(int $customsNetWeight) : \lujie\dpd\soap\Type\AdditionalInvoiceLine
    {
        $this->customsNetWeight = $customsNetWeight;
        return $this;
    }

    /**
     * @return int
     */
    public function getCustomsGrossWeight()
    {
        return $this->customsGrossWeight;
    }

    /**
     * @param int $customsGrossWeight
     * @return AdditionalInvoiceLine
     */
    public function withCustomsGrossWeight($customsGrossWeight)
    {
        $new = clone $this;
        $new->customsGrossWeight = $customsGrossWeight;

        return $new;
    }

    /**
     * @param int $customsGrossWeight
     * @return $this
     */
    public function setCustomsGrossWeight(int $customsGrossWeight) : \lujie\dpd\soap\Type\AdditionalInvoiceLine
    {
        $this->customsGrossWeight = $customsGrossWeight;
        return $this;
    }

    /**
     * @return string
     */
    public function getProductFabricComposition()
    {
        return $this->productFabricComposition;
    }

    /**
     * @param string $productFabricComposition
     * @return AdditionalInvoiceLine
     */
    public function withProductFabricComposition($productFabricComposition)
    {
        $new = clone $this;
        $new->productFabricComposition = $productFabricComposition;

        return $new;
    }

    /**
     * @param string $productFabricComposition
     * @return $this
     */
    public function setProductFabricComposition(string $productFabricComposition) : \lujie\dpd\soap\Type\AdditionalInvoiceLine
    {
        $this->productFabricComposition = $productFabricComposition;
        return $this;
    }

    /**
     * @return string
     */
    public function getProductCode()
    {
        return $this->productCode;
    }

    /**
     * @param string $productCode
     * @return AdditionalInvoiceLine
     */
    public function withProductCode($productCode)
    {
        $new = clone $this;
        $new->productCode = $productCode;

        return $new;
    }

    /**
     * @param string $productCode
     * @return $this
     */
    public function setProductCode(string $productCode) : \lujie\dpd\soap\Type\AdditionalInvoiceLine
    {
        $this->productCode = $productCode;
        return $this;
    }

    /**
     * @return string
     */
    public function getProductShortDescription()
    {
        return $this->productShortDescription;
    }

    /**
     * @param string $productShortDescription
     * @return AdditionalInvoiceLine
     */
    public function withProductShortDescription($productShortDescription)
    {
        $new = clone $this;
        $new->productShortDescription = $productShortDescription;

        return $new;
    }

    /**
     * @param string $productShortDescription
     * @return $this
     */
    public function setProductShortDescription(string $productShortDescription) : \lujie\dpd\soap\Type\AdditionalInvoiceLine
    {
        $this->productShortDescription = $productShortDescription;
        return $this;
    }
}

