<?php

namespace lujie\dpd\soap\Type;

use yii\base\BaseObject;

class International extends BaseObject
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
     * @var \lujie\dpd\soap\Type\AddressWithBusinessUnit
     */
    private $commercialInvoiceConsignee;

    /**
     * @var string
     */
    private $commercialInvoiceConsignorVatNumber;

    /**
     * @var \lujie\dpd\soap\Type\Address
     */
    private $commercialInvoiceConsignor;

    /**
     * @var \lujie\dpd\soap\Type\AdditionalInvoiceLine
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
     * @param bool $parcelType
     * @return $this
     */
    public function setParcelType(bool $parcelType) : \lujie\dpd\soap\Type\International
    {
        $this->parcelType = $parcelType;
        return $this;
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
     * @param int $customsAmount
     * @return $this
     */
    public function setCustomsAmount(int $customsAmount) : \lujie\dpd\soap\Type\International
    {
        $this->customsAmount = $customsAmount;
        return $this;
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
     * @param string $customsCurrency
     * @return $this
     */
    public function setCustomsCurrency(string $customsCurrency) : \lujie\dpd\soap\Type\International
    {
        $this->customsCurrency = $customsCurrency;
        return $this;
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
     * @param int $customsAmountExport
     * @return $this
     */
    public function setCustomsAmountExport(int $customsAmountExport) : \lujie\dpd\soap\Type\International
    {
        $this->customsAmountExport = $customsAmountExport;
        return $this;
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
     * @param string $customsCurrencyExport
     * @return $this
     */
    public function setCustomsCurrencyExport(string $customsCurrencyExport) : \lujie\dpd\soap\Type\International
    {
        $this->customsCurrencyExport = $customsCurrencyExport;
        return $this;
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
     * @param string $customsTerms
     * @return $this
     */
    public function setCustomsTerms(string $customsTerms) : \lujie\dpd\soap\Type\International
    {
        $this->customsTerms = $customsTerms;
        return $this;
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
     * @param string $customsPaper
     * @return $this
     */
    public function setCustomsPaper(string $customsPaper) : \lujie\dpd\soap\Type\International
    {
        $this->customsPaper = $customsPaper;
        return $this;
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
     * @param bool $customsEnclosure
     * @return $this
     */
    public function setCustomsEnclosure(bool $customsEnclosure) : \lujie\dpd\soap\Type\International
    {
        $this->customsEnclosure = $customsEnclosure;
        return $this;
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
     * @param string $customsInvoice
     * @return $this
     */
    public function setCustomsInvoice(string $customsInvoice) : \lujie\dpd\soap\Type\International
    {
        $this->customsInvoice = $customsInvoice;
        return $this;
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
     * @param int $customsInvoiceDate
     * @return $this
     */
    public function setCustomsInvoiceDate(int $customsInvoiceDate) : \lujie\dpd\soap\Type\International
    {
        $this->customsInvoiceDate = $customsInvoiceDate;
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
     * @return International
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
    public function setCustomsOrigin(string $customsOrigin) : \lujie\dpd\soap\Type\International
    {
        $this->customsOrigin = $customsOrigin;
        return $this;
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
     * @param string $customsOrder
     * @return $this
     */
    public function setCustomsOrder(string $customsOrder) : \lujie\dpd\soap\Type\International
    {
        $this->customsOrder = $customsOrder;
        return $this;
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
     * @param string $linehaul
     * @return $this
     */
    public function setLinehaul(string $linehaul) : \lujie\dpd\soap\Type\International
    {
        $this->linehaul = $linehaul;
        return $this;
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
     * @param string $shipMrn
     * @return $this
     */
    public function setShipMrn(string $shipMrn) : \lujie\dpd\soap\Type\International
    {
        $this->shipMrn = $shipMrn;
        return $this;
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
     * @param bool $collectiveCustomsClearance
     * @return $this
     */
    public function setCollectiveCustomsClearance(bool $collectiveCustomsClearance) : \lujie\dpd\soap\Type\International
    {
        $this->collectiveCustomsClearance = $collectiveCustomsClearance;
        return $this;
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
     * @param int $invoicePosition
     * @return $this
     */
    public function setInvoicePosition(int $invoicePosition) : \lujie\dpd\soap\Type\International
    {
        $this->invoicePosition = $invoicePosition;
        return $this;
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
     * @param string $comment1
     * @return $this
     */
    public function setComment1(string $comment1) : \lujie\dpd\soap\Type\International
    {
        $this->comment1 = $comment1;
        return $this;
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
     * @param string $comment2
     * @return $this
     */
    public function setComment2(string $comment2) : \lujie\dpd\soap\Type\International
    {
        $this->comment2 = $comment2;
        return $this;
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
     * @param int $numberOfArticle
     * @return $this
     */
    public function setNumberOfArticle(int $numberOfArticle) : \lujie\dpd\soap\Type\International
    {
        $this->numberOfArticle = $numberOfArticle;
        return $this;
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
     * @param string $countryRegistrationNumber
     * @return $this
     */
    public function setCountryRegistrationNumber(string $countryRegistrationNumber) : \lujie\dpd\soap\Type\International
    {
        $this->countryRegistrationNumber = $countryRegistrationNumber;
        return $this;
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
     * @param string $commercialInvoiceConsigneeVatNumber
     * @return $this
     */
    public function setCommercialInvoiceConsigneeVatNumber(string $commercialInvoiceConsigneeVatNumber) : \lujie\dpd\soap\Type\International
    {
        $this->commercialInvoiceConsigneeVatNumber = $commercialInvoiceConsigneeVatNumber;
        return $this;
    }

    /**
     * @return \lujie\dpd\soap\Type\AddressWithBusinessUnit
     */
    public function getCommercialInvoiceConsignee()
    {
        return $this->commercialInvoiceConsignee;
    }

    /**
     * @param \lujie\dpd\soap\Type\AddressWithBusinessUnit $commercialInvoiceConsignee
     * @return International
     */
    public function withCommercialInvoiceConsignee($commercialInvoiceConsignee)
    {
        $new = clone $this;
        $new->commercialInvoiceConsignee = $commercialInvoiceConsignee;

        return $new;
    }

    /**
     * @param \lujie\dpd\soap\Type\AddressWithBusinessUnit $commercialInvoiceConsignee
     * @return $this
     */
    public function setCommercialInvoiceConsignee($commercialInvoiceConsignee) : \lujie\dpd\soap\Type\International
    {
        $this->commercialInvoiceConsignee = $commercialInvoiceConsignee;
        return $this;
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
     * @param string $commercialInvoiceConsignorVatNumber
     * @return $this
     */
    public function setCommercialInvoiceConsignorVatNumber(string $commercialInvoiceConsignorVatNumber) : \lujie\dpd\soap\Type\International
    {
        $this->commercialInvoiceConsignorVatNumber = $commercialInvoiceConsignorVatNumber;
        return $this;
    }

    /**
     * @return \lujie\dpd\soap\Type\Address
     */
    public function getCommercialInvoiceConsignor()
    {
        return $this->commercialInvoiceConsignor;
    }

    /**
     * @param \lujie\dpd\soap\Type\Address $commercialInvoiceConsignor
     * @return International
     */
    public function withCommercialInvoiceConsignor($commercialInvoiceConsignor)
    {
        $new = clone $this;
        $new->commercialInvoiceConsignor = $commercialInvoiceConsignor;

        return $new;
    }

    /**
     * @param \lujie\dpd\soap\Type\Address $commercialInvoiceConsignor
     * @return $this
     */
    public function setCommercialInvoiceConsignor($commercialInvoiceConsignor) : \lujie\dpd\soap\Type\International
    {
        $this->commercialInvoiceConsignor = $commercialInvoiceConsignor;
        return $this;
    }

    /**
     * @return \lujie\dpd\soap\Type\AdditionalInvoiceLine
     */
    public function getAdditionalInvoiceLines()
    {
        return $this->additionalInvoiceLines;
    }

    /**
     * @param \lujie\dpd\soap\Type\AdditionalInvoiceLine $additionalInvoiceLines
     * @return International
     */
    public function withAdditionalInvoiceLines($additionalInvoiceLines)
    {
        $new = clone $this;
        $new->additionalInvoiceLines = $additionalInvoiceLines;

        return $new;
    }

    /**
     * @param \lujie\dpd\soap\Type\AdditionalInvoiceLine $additionalInvoiceLines
     * @return $this
     */
    public function setAdditionalInvoiceLines($additionalInvoiceLines) : \lujie\dpd\soap\Type\International
    {
        $this->additionalInvoiceLines = $additionalInvoiceLines;
        return $this;
    }
}

