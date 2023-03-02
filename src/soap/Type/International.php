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
    public function getParcelType() : bool
    {
        return $this->parcelType;
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
     * @param bool $parcelType
     * @return International
     */
    public function withParcelType(bool $parcelType) : \lujie\dpd\soap\Type\International
    {
        $new = clone $this;
        $new->parcelType = $parcelType;

        return $new;
    }

    /**
     * @return int
     */
    public function getCustomsAmount() : int
    {
        return $this->customsAmount;
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
     * @param int $customsAmount
     * @return International
     */
    public function withCustomsAmount(int $customsAmount) : \lujie\dpd\soap\Type\International
    {
        $new = clone $this;
        $new->customsAmount = $customsAmount;

        return $new;
    }

    /**
     * @return string
     */
    public function getCustomsCurrency() : string
    {
        return $this->customsCurrency;
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
     * @param string $customsCurrency
     * @return International
     */
    public function withCustomsCurrency(string $customsCurrency) : \lujie\dpd\soap\Type\International
    {
        $new = clone $this;
        $new->customsCurrency = $customsCurrency;

        return $new;
    }

    /**
     * @return int
     */
    public function getCustomsAmountExport() : int
    {
        return $this->customsAmountExport;
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
     * @param int $customsAmountExport
     * @return International
     */
    public function withCustomsAmountExport(int $customsAmountExport) : \lujie\dpd\soap\Type\International
    {
        $new = clone $this;
        $new->customsAmountExport = $customsAmountExport;

        return $new;
    }

    /**
     * @return string
     */
    public function getCustomsCurrencyExport() : string
    {
        return $this->customsCurrencyExport;
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
     * @param string $customsCurrencyExport
     * @return International
     */
    public function withCustomsCurrencyExport(string $customsCurrencyExport) : \lujie\dpd\soap\Type\International
    {
        $new = clone $this;
        $new->customsCurrencyExport = $customsCurrencyExport;

        return $new;
    }

    /**
     * @return string
     */
    public function getCustomsTerms() : string
    {
        return $this->customsTerms;
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
     * @param string $customsTerms
     * @return International
     */
    public function withCustomsTerms(string $customsTerms) : \lujie\dpd\soap\Type\International
    {
        $new = clone $this;
        $new->customsTerms = $customsTerms;

        return $new;
    }

    /**
     * @return string
     */
    public function getCustomsPaper() : string
    {
        return $this->customsPaper;
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
     * @param string $customsPaper
     * @return International
     */
    public function withCustomsPaper(string $customsPaper) : \lujie\dpd\soap\Type\International
    {
        $new = clone $this;
        $new->customsPaper = $customsPaper;

        return $new;
    }

    /**
     * @return bool
     */
    public function getCustomsEnclosure() : bool
    {
        return $this->customsEnclosure;
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
     * @param bool $customsEnclosure
     * @return International
     */
    public function withCustomsEnclosure(bool $customsEnclosure) : \lujie\dpd\soap\Type\International
    {
        $new = clone $this;
        $new->customsEnclosure = $customsEnclosure;

        return $new;
    }

    /**
     * @return string
     */
    public function getCustomsInvoice() : string
    {
        return $this->customsInvoice;
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
     * @param string $customsInvoice
     * @return International
     */
    public function withCustomsInvoice(string $customsInvoice) : \lujie\dpd\soap\Type\International
    {
        $new = clone $this;
        $new->customsInvoice = $customsInvoice;

        return $new;
    }

    /**
     * @return int
     */
    public function getCustomsInvoiceDate() : int
    {
        return $this->customsInvoiceDate;
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
     * @param int $customsInvoiceDate
     * @return International
     */
    public function withCustomsInvoiceDate(int $customsInvoiceDate) : \lujie\dpd\soap\Type\International
    {
        $new = clone $this;
        $new->customsInvoiceDate = $customsInvoiceDate;

        return $new;
    }

    /**
     * @return string
     */
    public function getCustomsOrigin() : string
    {
        return $this->customsOrigin;
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
     * @param string $customsOrigin
     * @return International
     */
    public function withCustomsOrigin(string $customsOrigin) : \lujie\dpd\soap\Type\International
    {
        $new = clone $this;
        $new->customsOrigin = $customsOrigin;

        return $new;
    }

    /**
     * @return string
     */
    public function getCustomsOrder() : string
    {
        return $this->customsOrder;
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
     * @param string $customsOrder
     * @return International
     */
    public function withCustomsOrder(string $customsOrder) : \lujie\dpd\soap\Type\International
    {
        $new = clone $this;
        $new->customsOrder = $customsOrder;

        return $new;
    }

    /**
     * @return string
     */
    public function getLinehaul() : string
    {
        return $this->linehaul;
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
     * @param string $linehaul
     * @return International
     */
    public function withLinehaul(string $linehaul) : \lujie\dpd\soap\Type\International
    {
        $new = clone $this;
        $new->linehaul = $linehaul;

        return $new;
    }

    /**
     * @return string
     */
    public function getShipMrn() : string
    {
        return $this->shipMrn;
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
     * @param string $shipMrn
     * @return International
     */
    public function withShipMrn(string $shipMrn) : \lujie\dpd\soap\Type\International
    {
        $new = clone $this;
        $new->shipMrn = $shipMrn;

        return $new;
    }

    /**
     * @return bool
     */
    public function getCollectiveCustomsClearance() : bool
    {
        return $this->collectiveCustomsClearance;
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
     * @param bool $collectiveCustomsClearance
     * @return International
     */
    public function withCollectiveCustomsClearance(bool $collectiveCustomsClearance) : \lujie\dpd\soap\Type\International
    {
        $new = clone $this;
        $new->collectiveCustomsClearance = $collectiveCustomsClearance;

        return $new;
    }

    /**
     * @return int
     */
    public function getInvoicePosition() : int
    {
        return $this->invoicePosition;
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
     * @param int $invoicePosition
     * @return International
     */
    public function withInvoicePosition(int $invoicePosition) : \lujie\dpd\soap\Type\International
    {
        $new = clone $this;
        $new->invoicePosition = $invoicePosition;

        return $new;
    }

    /**
     * @return string
     */
    public function getComment1() : string
    {
        return $this->comment1;
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
     * @param string $comment1
     * @return International
     */
    public function withComment1(string $comment1) : \lujie\dpd\soap\Type\International
    {
        $new = clone $this;
        $new->comment1 = $comment1;

        return $new;
    }

    /**
     * @return string
     */
    public function getComment2() : string
    {
        return $this->comment2;
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
     * @param string $comment2
     * @return International
     */
    public function withComment2(string $comment2) : \lujie\dpd\soap\Type\International
    {
        $new = clone $this;
        $new->comment2 = $comment2;

        return $new;
    }

    /**
     * @return int
     */
    public function getNumberOfArticle() : int
    {
        return $this->numberOfArticle;
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
     * @param int $numberOfArticle
     * @return International
     */
    public function withNumberOfArticle(int $numberOfArticle) : \lujie\dpd\soap\Type\International
    {
        $new = clone $this;
        $new->numberOfArticle = $numberOfArticle;

        return $new;
    }

    /**
     * @return string
     */
    public function getCountryRegistrationNumber() : string
    {
        return $this->countryRegistrationNumber;
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
     * @param string $countryRegistrationNumber
     * @return International
     */
    public function withCountryRegistrationNumber(string $countryRegistrationNumber) : \lujie\dpd\soap\Type\International
    {
        $new = clone $this;
        $new->countryRegistrationNumber = $countryRegistrationNumber;

        return $new;
    }

    /**
     * @return string
     */
    public function getCommercialInvoiceConsigneeVatNumber() : string
    {
        return $this->commercialInvoiceConsigneeVatNumber;
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
     * @param string $commercialInvoiceConsigneeVatNumber
     * @return International
     */
    public function withCommercialInvoiceConsigneeVatNumber(string $commercialInvoiceConsigneeVatNumber) : \lujie\dpd\soap\Type\International
    {
        $new = clone $this;
        $new->commercialInvoiceConsigneeVatNumber = $commercialInvoiceConsigneeVatNumber;

        return $new;
    }

    /**
     * @return \lujie\dpd\soap\Type\AddressWithBusinessUnit
     */
    public function getCommercialInvoiceConsignee() : \lujie\dpd\soap\Type\AddressWithBusinessUnit
    {
        return $this->commercialInvoiceConsignee;
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
     * @param \lujie\dpd\soap\Type\AddressWithBusinessUnit $commercialInvoiceConsignee
     * @return International
     */
    public function withCommercialInvoiceConsignee(\lujie\dpd\soap\Type\AddressWithBusinessUnit $commercialInvoiceConsignee) : \lujie\dpd\soap\Type\International
    {
        $new = clone $this;
        $new->commercialInvoiceConsignee = $commercialInvoiceConsignee;

        return $new;
    }

    /**
     * @return string
     */
    public function getCommercialInvoiceConsignorVatNumber() : string
    {
        return $this->commercialInvoiceConsignorVatNumber;
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
     * @param string $commercialInvoiceConsignorVatNumber
     * @return International
     */
    public function withCommercialInvoiceConsignorVatNumber(string $commercialInvoiceConsignorVatNumber) : \lujie\dpd\soap\Type\International
    {
        $new = clone $this;
        $new->commercialInvoiceConsignorVatNumber = $commercialInvoiceConsignorVatNumber;

        return $new;
    }

    /**
     * @return \lujie\dpd\soap\Type\Address
     */
    public function getCommercialInvoiceConsignor() : \lujie\dpd\soap\Type\Address
    {
        return $this->commercialInvoiceConsignor;
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
     * @param \lujie\dpd\soap\Type\Address $commercialInvoiceConsignor
     * @return International
     */
    public function withCommercialInvoiceConsignor(\lujie\dpd\soap\Type\Address $commercialInvoiceConsignor) : \lujie\dpd\soap\Type\International
    {
        $new = clone $this;
        $new->commercialInvoiceConsignor = $commercialInvoiceConsignor;

        return $new;
    }

    /**
     * @return \lujie\dpd\soap\Type\AdditionalInvoiceLine
     */
    public function getAdditionalInvoiceLines() : \lujie\dpd\soap\Type\AdditionalInvoiceLine
    {
        return $this->additionalInvoiceLines;
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

    /**
     * @param \lujie\dpd\soap\Type\AdditionalInvoiceLine $additionalInvoiceLines
     * @return International
     */
    public function withAdditionalInvoiceLines(\lujie\dpd\soap\Type\AdditionalInvoiceLine $additionalInvoiceLines) : \lujie\dpd\soap\Type\International
    {
        $new = clone $this;
        $new->additionalInvoiceLines = $additionalInvoiceLines;

        return $new;
    }


}

