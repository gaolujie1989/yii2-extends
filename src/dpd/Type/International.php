<?php

namespace dpd\Type;

use Phpro\SoapClient\Type\RequestInterface;

class International implements RequestInterface
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
     * @var string
     */
    private $customsTerms;

    /**
     * @var string
     */
    private $customsContent;

    /**
     * @var string
     */
    private $customsTarif;

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
     * @var int
     */
    private $customsAmountParcel;

    /**
     * @var string
     */
    private $customsOrigin;

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
     * @var string
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
     * @var string
     */
    private $commercialInvoiceConsigneeVatNumber;

    /**
     * @var \dpd\Type\Address
     */
    private $commercialInvoiceConsignee;

    /**
     * Constructor
     *
     * @var bool $parcelType
     * @var int $customsAmount
     * @var string $customsCurrency
     * @var string $customsTerms
     * @var string $customsContent
     * @var string $customsTarif
     * @var string $customsPaper
     * @var bool $customsEnclosure
     * @var string $customsInvoice
     * @var int $customsInvoiceDate
     * @var int $customsAmountParcel
     * @var string $customsOrigin
     * @var string $linehaul
     * @var string $shipMrn
     * @var bool $collectiveCustomsClearance
     * @var string $invoicePosition
     * @var string $comment1
     * @var string $comment2
     * @var string $commercialInvoiceConsigneeVatNumber
     * @var \dpd\Type\Address $commercialInvoiceConsignee
     */
    public function __construct($parcelType, $customsAmount, $customsCurrency, $customsTerms, $customsContent, $customsTarif, $customsPaper, $customsEnclosure, $customsInvoice, $customsInvoiceDate, $customsAmountParcel, $customsOrigin, $linehaul, $shipMrn, $collectiveCustomsClearance, $invoicePosition, $comment1, $comment2, $commercialInvoiceConsigneeVatNumber, $commercialInvoiceConsignee)
    {
        $this->parcelType = $parcelType;
        $this->customsAmount = $customsAmount;
        $this->customsCurrency = $customsCurrency;
        $this->customsTerms = $customsTerms;
        $this->customsContent = $customsContent;
        $this->customsTarif = $customsTarif;
        $this->customsPaper = $customsPaper;
        $this->customsEnclosure = $customsEnclosure;
        $this->customsInvoice = $customsInvoice;
        $this->customsInvoiceDate = $customsInvoiceDate;
        $this->customsAmountParcel = $customsAmountParcel;
        $this->customsOrigin = $customsOrigin;
        $this->linehaul = $linehaul;
        $this->shipMrn = $shipMrn;
        $this->collectiveCustomsClearance = $collectiveCustomsClearance;
        $this->invoicePosition = $invoicePosition;
        $this->comment1 = $comment1;
        $this->comment2 = $comment2;
        $this->commercialInvoiceConsigneeVatNumber = $commercialInvoiceConsigneeVatNumber;
        $this->commercialInvoiceConsignee = $commercialInvoiceConsignee;
    }

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
    public function getCustomsContent()
    {
        return $this->customsContent;
    }

    /**
     * @param string $customsContent
     * @return International
     */
    public function withCustomsContent($customsContent)
    {
        $new = clone $this;
        $new->customsContent = $customsContent;

        return $new;
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
     * @return International
     */
    public function withCustomsTarif($customsTarif)
    {
        $new = clone $this;
        $new->customsTarif = $customsTarif;

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
     * @return int
     */
    public function getCustomsAmountParcel()
    {
        return $this->customsAmountParcel;
    }

    /**
     * @param int $customsAmountParcel
     * @return International
     */
    public function withCustomsAmountParcel($customsAmountParcel)
    {
        $new = clone $this;
        $new->customsAmountParcel = $customsAmountParcel;

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
     * @return string
     */
    public function getInvoicePosition()
    {
        return $this->invoicePosition;
    }

    /**
     * @param string $invoicePosition
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
     * @return \dpd\Type\Address
     */
    public function getCommercialInvoiceConsignee()
    {
        return $this->commercialInvoiceConsignee;
    }

    /**
     * @param \dpd\Type\Address $commercialInvoiceConsignee
     * @return International
     */
    public function withCommercialInvoiceConsignee($commercialInvoiceConsignee)
    {
        $new = clone $this;
        $new->commercialInvoiceConsignee = $commercialInvoiceConsignee;

        return $new;
    }


}

