<?php

namespace dpd\Type;

class International
{

    /**
     * @var bool
     */
    private $parcelType;

    /**
     * @var int
     */
    private $customsAmount;

    /**
     * @var string
     */
    private $customsCurrency;

    /**
     * @var int
     */
    private $customsAmountExport;

    /**
     * @var string
     */
    private $customsCurrencyExport;

    /**
     * @var string
     */
    private $customsTerms;

    /**
     * @var string
     */
    private $customsPaper;

    /**
     * @var bool
     */
    private $customsEnclosure;

    /**
     * @var string
     */
    private $customsInvoice;

    /**
     * @var int
     */
    private $customsInvoiceDate;

    /**
     * @var string
     */
    private $customsOrigin;

    /**
     * @var string
     */
    private $customsOrder;

    /**
     * @var string
     */
    private $linehaul;

    /**
     * @var string
     */
    private $shipMrn;

    /**
     * @var bool
     */
    private $collectiveCustomsClearance;

    /**
     * @var int
     */
    private $invoicePosition;

    /**
     * @var string
     */
    private $comment1;

    /**
     * @var string
     */
    private $comment2;

    /**
     * @var int
     */
    private $numberOfArticle;

    /**
     * @var string
     */
    private $countryRegistrationNumber;

    /**
     * @var string
     */
    private $commercialInvoiceConsigneeVatNumber;

    /**
     * @var \dpd\Type\AddressWithBusinessUnit
     */
    private $commercialInvoiceConsignee;

    /**
     * @var string
     */
    private $commercialInvoiceConsignorVatNumber;

    /**
     * @var \dpd\Type\Address
     */
    private $commercialInvoiceConsignor;

    /**
     * @var \dpd\Type\AdditionalInvoiceLine
     */
    private $additionalInvoiceLines;

    /**
     * @return bool
     */
    public function getParcelType()
    {
        return $this->parcelType;
    }

    /**
     * @param bool $parcelType
     * @return International
     */
    public function withParcelType($parcelType)
    {
        $new = clone $this;
        $new->parcelType = $parcelType;

        return $new;
    }

    /**
     * @return int
     */
    public function getCustomsAmount()
    {
        return $this->customsAmount;
    }

    /**
     * @param int $customsAmount
     * @return International
     */
    public function withCustomsAmount($customsAmount)
    {
        $new = clone $this;
        $new->customsAmount = $customsAmount;

        return $new;
    }

    /**
     * @return string
     */
    public function getCustomsCurrency()
    {
        return $this->customsCurrency;
    }

    /**
     * @param string $customsCurrency
     * @return International
     */
    public function withCustomsCurrency($customsCurrency)
    {
        $new = clone $this;
        $new->customsCurrency = $customsCurrency;

        return $new;
    }

    /**
     * @return int
     */
    public function getCustomsAmountExport()
    {
        return $this->customsAmountExport;
    }

    /**
     * @param int $customsAmountExport
     * @return International
     */
    public function withCustomsAmountExport($customsAmountExport)
    {
        $new = clone $this;
        $new->customsAmountExport = $customsAmountExport;

        return $new;
    }

    /**
     * @return string
     */
    public function getCustomsCurrencyExport()
    {
        return $this->customsCurrencyExport;
    }

    /**
     * @param string $customsCurrencyExport
     * @return International
     */
    public function withCustomsCurrencyExport($customsCurrencyExport)
    {
        $new = clone $this;
        $new->customsCurrencyExport = $customsCurrencyExport;

        return $new;
    }

    /**
     * @return string
     */
    public function getCustomsTerms()
    {
        return $this->customsTerms;
    }

    /**
     * @param string $customsTerms
     * @return International
     */
    public function withCustomsTerms($customsTerms)
    {
        $new = clone $this;
        $new->customsTerms = $customsTerms;

        return $new;
    }

    /**
     * @return string
     */
    public function getCustomsPaper()
    {
        return $this->customsPaper;
    }

    /**
     * @param string $customsPaper
     * @return International
     */
    public function withCustomsPaper($customsPaper)
    {
        $new = clone $this;
        $new->customsPaper = $customsPaper;

        return $new;
    }

    /**
     * @return bool
     */
    public function getCustomsEnclosure()
    {
        return $this->customsEnclosure;
    }

    /**
     * @param bool $customsEnclosure
     * @return International
     */
    public function withCustomsEnclosure($customsEnclosure)
    {
        $new = clone $this;
        $new->customsEnclosure = $customsEnclosure;

        return $new;
    }

    /**
     * @return string
     */
    public function getCustomsInvoice()
    {
        return $this->customsInvoice;
    }

    /**
     * @param string $customsInvoice
     * @return International
     */
    public function withCustomsInvoice($customsInvoice)
    {
        $new = clone $this;
        $new->customsInvoice = $customsInvoice;

        return $new;
    }

    /**
     * @return int
     */
    public function getCustomsInvoiceDate()
    {
        return $this->customsInvoiceDate;
    }

    /**
     * @param int $customsInvoiceDate
     * @return International
     */
    public function withCustomsInvoiceDate($customsInvoiceDate)
    {
        $new = clone $this;
        $new->customsInvoiceDate = $customsInvoiceDate;

        return $new;
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
     * @return International
     */
    public function withCustomsOrigin($customsOrigin)
    {
        $new = clone $this;
        $new->customsOrigin = $customsOrigin;

        return $new;
    }

    /**
     * @return string
     */
    public function getCustomsOrder()
    {
        return $this->customsOrder;
    }

    /**
     * @param string $customsOrder
     * @return International
     */
    public function withCustomsOrder($customsOrder)
    {
        $new = clone $this;
        $new->customsOrder = $customsOrder;

        return $new;
    }

    /**
     * @return string
     */
    public function getLinehaul()
    {
        return $this->linehaul;
    }

    /**
     * @param string $linehaul
     * @return International
     */
    public function withLinehaul($linehaul)
    {
        $new = clone $this;
        $new->linehaul = $linehaul;

        return $new;
    }

    /**
     * @return string
     */
    public function getShipMrn()
    {
        return $this->shipMrn;
    }

    /**
     * @param string $shipMrn
     * @return International
     */
    public function withShipMrn($shipMrn)
    {
        $new = clone $this;
        $new->shipMrn = $shipMrn;

        return $new;
    }

    /**
     * @return bool
     */
    public function getCollectiveCustomsClearance()
    {
        return $this->collectiveCustomsClearance;
    }

    /**
     * @param bool $collectiveCustomsClearance
     * @return International
     */
    public function withCollectiveCustomsClearance($collectiveCustomsClearance)
    {
        $new = clone $this;
        $new->collectiveCustomsClearance = $collectiveCustomsClearance;

        return $new;
    }

    /**
     * @return int
     */
    public function getInvoicePosition()
    {
        return $this->invoicePosition;
    }

    /**
     * @param int $invoicePosition
     * @return International
     */
    public function withInvoicePosition($invoicePosition)
    {
        $new = clone $this;
        $new->invoicePosition = $invoicePosition;

        return $new;
    }

    /**
     * @return string
     */
    public function getComment1()
    {
        return $this->comment1;
    }

    /**
     * @param string $comment1
     * @return International
     */
    public function withComment1($comment1)
    {
        $new = clone $this;
        $new->comment1 = $comment1;

        return $new;
    }

    /**
     * @return string
     */
    public function getComment2()
    {
        return $this->comment2;
    }

    /**
     * @param string $comment2
     * @return International
     */
    public function withComment2($comment2)
    {
        $new = clone $this;
        $new->comment2 = $comment2;

        return $new;
    }

    /**
     * @return int
     */
    public function getNumberOfArticle()
    {
        return $this->numberOfArticle;
    }

    /**
     * @param int $numberOfArticle
     * @return International
     */
    public function withNumberOfArticle($numberOfArticle)
    {
        $new = clone $this;
        $new->numberOfArticle = $numberOfArticle;

        return $new;
    }

    /**
     * @return string
     */
    public function getCountryRegistrationNumber()
    {
        return $this->countryRegistrationNumber;
    }

    /**
     * @param string $countryRegistrationNumber
     * @return International
     */
    public function withCountryRegistrationNumber($countryRegistrationNumber)
    {
        $new = clone $this;
        $new->countryRegistrationNumber = $countryRegistrationNumber;

        return $new;
    }

    /**
     * @return string
     */
    public function getCommercialInvoiceConsigneeVatNumber()
    {
        return $this->commercialInvoiceConsigneeVatNumber;
    }

    /**
     * @param string $commercialInvoiceConsigneeVatNumber
     * @return International
     */
    public function withCommercialInvoiceConsigneeVatNumber($commercialInvoiceConsigneeVatNumber)
    {
        $new = clone $this;
        $new->commercialInvoiceConsigneeVatNumber = $commercialInvoiceConsigneeVatNumber;

        return $new;
    }

    /**
     * @return \dpd\Type\AddressWithBusinessUnit
     */
    public function getCommercialInvoiceConsignee()
    {
        return $this->commercialInvoiceConsignee;
    }

    /**
     * @param \dpd\Type\AddressWithBusinessUnit $commercialInvoiceConsignee
     * @return International
     */
    public function withCommercialInvoiceConsignee($commercialInvoiceConsignee)
    {
        $new = clone $this;
        $new->commercialInvoiceConsignee = $commercialInvoiceConsignee;

        return $new;
    }

    /**
     * @return string
     */
    public function getCommercialInvoiceConsignorVatNumber()
    {
        return $this->commercialInvoiceConsignorVatNumber;
    }

    /**
     * @param string $commercialInvoiceConsignorVatNumber
     * @return International
     */
    public function withCommercialInvoiceConsignorVatNumber($commercialInvoiceConsignorVatNumber)
    {
        $new = clone $this;
        $new->commercialInvoiceConsignorVatNumber = $commercialInvoiceConsignorVatNumber;

        return $new;
    }

    /**
     * @return \dpd\Type\Address
     */
    public function getCommercialInvoiceConsignor()
    {
        return $this->commercialInvoiceConsignor;
    }

    /**
     * @param \dpd\Type\Address $commercialInvoiceConsignor
     * @return International
     */
    public function withCommercialInvoiceConsignor($commercialInvoiceConsignor)
    {
        $new = clone $this;
        $new->commercialInvoiceConsignor = $commercialInvoiceConsignor;

        return $new;
    }

    /**
     * @return \dpd\Type\AdditionalInvoiceLine
     */
    public function getAdditionalInvoiceLines()
    {
        return $this->additionalInvoiceLines;
    }

    /**
     * @param \dpd\Type\AdditionalInvoiceLine $additionalInvoiceLines
     * @return International
     */
    public function withAdditionalInvoiceLines($additionalInvoiceLines)
    {
        $new = clone $this;
        $new->additionalInvoiceLines = $additionalInvoiceLines;

        return $new;
    }


}

