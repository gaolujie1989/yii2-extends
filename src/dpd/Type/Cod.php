<?php

namespace dpd\Type;

use Phpro\SoapClient\Type\RequestInterface;

class Cod implements RequestInterface
{

    /**
     * @var int
     */
    private $amount;

    /**
     * @var string
     */
    private $currency;

    /**
     * @var int
     */
    private $inkasso;

    /**
     * @var string
     */
    private $purpose;

    /**
     * @var string
     */
    private $bankCode;

    /**
     * @var string
     */
    private $bankName;

    /**
     * @var string
     */
    private $bankAccountNumber;

    /**
     * @var string
     */
    private $bankAccountHolder;

    /**
     * @var string
     */
    private $iban;

    /**
     * @var string
     */
    private $bic;

    /**
     * Constructor
     *
     * @var int $amount
     * @var string $currency
     * @var int $inkasso
     * @var string $purpose
     * @var string $bankCode
     * @var string $bankName
     * @var string $bankAccountNumber
     * @var string $bankAccountHolder
     * @var string $iban
     * @var string $bic
     */
    public function __construct($amount, $currency, $inkasso, $purpose, $bankCode, $bankName, $bankAccountNumber, $bankAccountHolder, $iban, $bic)
    {
        $this->amount = $amount;
        $this->currency = $currency;
        $this->inkasso = $inkasso;
        $this->purpose = $purpose;
        $this->bankCode = $bankCode;
        $this->bankName = $bankName;
        $this->bankAccountNumber = $bankAccountNumber;
        $this->bankAccountHolder = $bankAccountHolder;
        $this->iban = $iban;
        $this->bic = $bic;
    }

    /**
     * @return int
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param int $amount
     * @return Cod
     */
    public function withAmount($amount)
    {
        $new = clone $this;
        $new->amount = $amount;

        return $new;
    }

    /**
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @param string $currency
     * @return Cod
     */
    public function withCurrency($currency)
    {
        $new = clone $this;
        $new->currency = $currency;

        return $new;
    }

    /**
     * @return int
     */
    public function getInkasso()
    {
        return $this->inkasso;
    }

    /**
     * @param int $inkasso
     * @return Cod
     */
    public function withInkasso($inkasso)
    {
        $new = clone $this;
        $new->inkasso = $inkasso;

        return $new;
    }

    /**
     * @return string
     */
    public function getPurpose()
    {
        return $this->purpose;
    }

    /**
     * @param string $purpose
     * @return Cod
     */
    public function withPurpose($purpose)
    {
        $new = clone $this;
        $new->purpose = $purpose;

        return $new;
    }

    /**
     * @return string
     */
    public function getBankCode()
    {
        return $this->bankCode;
    }

    /**
     * @param string $bankCode
     * @return Cod
     */
    public function withBankCode($bankCode)
    {
        $new = clone $this;
        $new->bankCode = $bankCode;

        return $new;
    }

    /**
     * @return string
     */
    public function getBankName()
    {
        return $this->bankName;
    }

    /**
     * @param string $bankName
     * @return Cod
     */
    public function withBankName($bankName)
    {
        $new = clone $this;
        $new->bankName = $bankName;

        return $new;
    }

    /**
     * @return string
     */
    public function getBankAccountNumber()
    {
        return $this->bankAccountNumber;
    }

    /**
     * @param string $bankAccountNumber
     * @return Cod
     */
    public function withBankAccountNumber($bankAccountNumber)
    {
        $new = clone $this;
        $new->bankAccountNumber = $bankAccountNumber;

        return $new;
    }

    /**
     * @return string
     */
    public function getBankAccountHolder()
    {
        return $this->bankAccountHolder;
    }

    /**
     * @param string $bankAccountHolder
     * @return Cod
     */
    public function withBankAccountHolder($bankAccountHolder)
    {
        $new = clone $this;
        $new->bankAccountHolder = $bankAccountHolder;

        return $new;
    }

    /**
     * @return string
     */
    public function getIban()
    {
        return $this->iban;
    }

    /**
     * @param string $iban
     * @return Cod
     */
    public function withIban($iban)
    {
        $new = clone $this;
        $new->iban = $iban;

        return $new;
    }

    /**
     * @return string
     */
    public function getBic()
    {
        return $this->bic;
    }

    /**
     * @param string $bic
     * @return Cod
     */
    public function withBic($bic)
    {
        $new = clone $this;
        $new->bic = $bic;

        return $new;
    }


}

