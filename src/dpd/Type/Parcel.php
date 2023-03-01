<?php

namespace dpd\Type;

class Parcel
{

    /**
     * @var string
     */
    private $parcelLabelNumber;

    /**
     * @var string
     */
    private $customerReferenceNumber1;

    /**
     * @var string
     */
    private $customerReferenceNumber2;

    /**
     * @var string
     */
    private $customerReferenceNumber3;

    /**
     * @var string
     */
    private $customerReferenceNumber4;

    /**
     * @var bool
     */
    private $swap;

    /**
     * @var int
     */
    private $volume;

    /**
     * @var int
     */
    private $weight;

    /**
     * @var bool
     */
    private $hazardousLimitedQuantities;

    /**
     * @var \dpd\Type\HigherInsurance
     */
    private $higherInsurance;

    /**
     * @var string
     */
    private $content;

    /**
     * @var int
     */
    private $addService;

    /**
     * @var int
     */
    private $messageNumber;

    /**
     * @var string
     */
    private $function;

    /**
     * @var string
     */
    private $parameter;

    /**
     * @var \dpd\Type\Hazardous
     */
    private $hazardous;

    /**
     * @var bool
     */
    private $printInfo1OnParcelLabel;

    /**
     * @var string
     */
    private $info1;

    /**
     * @var string
     */
    private $info2;

    /**
     * @var bool
     */
    private $returns;

    /**
     * @var string
     */
    private $parcelClass;

    /**
     * @return string
     */
    public function getParcelLabelNumber()
    {
        return $this->parcelLabelNumber;
    }

    /**
     * @param string $parcelLabelNumber
     * @return Parcel
     */
    public function withParcelLabelNumber($parcelLabelNumber)
    {
        $new = clone $this;
        $new->parcelLabelNumber = $parcelLabelNumber;

        return $new;
    }

    /**
     * @return string
     */
    public function getCustomerReferenceNumber1()
    {
        return $this->customerReferenceNumber1;
    }

    /**
     * @param string $customerReferenceNumber1
     * @return Parcel
     */
    public function withCustomerReferenceNumber1($customerReferenceNumber1)
    {
        $new = clone $this;
        $new->customerReferenceNumber1 = $customerReferenceNumber1;

        return $new;
    }

    /**
     * @return string
     */
    public function getCustomerReferenceNumber2()
    {
        return $this->customerReferenceNumber2;
    }

    /**
     * @param string $customerReferenceNumber2
     * @return Parcel
     */
    public function withCustomerReferenceNumber2($customerReferenceNumber2)
    {
        $new = clone $this;
        $new->customerReferenceNumber2 = $customerReferenceNumber2;

        return $new;
    }

    /**
     * @return string
     */
    public function getCustomerReferenceNumber3()
    {
        return $this->customerReferenceNumber3;
    }

    /**
     * @param string $customerReferenceNumber3
     * @return Parcel
     */
    public function withCustomerReferenceNumber3($customerReferenceNumber3)
    {
        $new = clone $this;
        $new->customerReferenceNumber3 = $customerReferenceNumber3;

        return $new;
    }

    /**
     * @return string
     */
    public function getCustomerReferenceNumber4()
    {
        return $this->customerReferenceNumber4;
    }

    /**
     * @param string $customerReferenceNumber4
     * @return Parcel
     */
    public function withCustomerReferenceNumber4($customerReferenceNumber4)
    {
        $new = clone $this;
        $new->customerReferenceNumber4 = $customerReferenceNumber4;

        return $new;
    }

    /**
     * @return bool
     */
    public function getSwap()
    {
        return $this->swap;
    }

    /**
     * @param bool $swap
     * @return Parcel
     */
    public function withSwap($swap)
    {
        $new = clone $this;
        $new->swap = $swap;

        return $new;
    }

    /**
     * @return int
     */
    public function getVolume()
    {
        return $this->volume;
    }

    /**
     * @param int $volume
     * @return Parcel
     */
    public function withVolume($volume)
    {
        $new = clone $this;
        $new->volume = $volume;

        return $new;
    }

    /**
     * @return int
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * @param int $weight
     * @return Parcel
     */
    public function withWeight($weight)
    {
        $new = clone $this;
        $new->weight = $weight;

        return $new;
    }

    /**
     * @return bool
     */
    public function getHazardousLimitedQuantities()
    {
        return $this->hazardousLimitedQuantities;
    }

    /**
     * @param bool $hazardousLimitedQuantities
     * @return Parcel
     */
    public function withHazardousLimitedQuantities($hazardousLimitedQuantities)
    {
        $new = clone $this;
        $new->hazardousLimitedQuantities = $hazardousLimitedQuantities;

        return $new;
    }

    /**
     * @return \dpd\Type\HigherInsurance
     */
    public function getHigherInsurance()
    {
        return $this->higherInsurance;
    }

    /**
     * @param \dpd\Type\HigherInsurance $higherInsurance
     * @return Parcel
     */
    public function withHigherInsurance($higherInsurance)
    {
        $new = clone $this;
        $new->higherInsurance = $higherInsurance;

        return $new;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param string $content
     * @return Parcel
     */
    public function withContent($content)
    {
        $new = clone $this;
        $new->content = $content;

        return $new;
    }

    /**
     * @return int
     */
    public function getAddService()
    {
        return $this->addService;
    }

    /**
     * @param int $addService
     * @return Parcel
     */
    public function withAddService($addService)
    {
        $new = clone $this;
        $new->addService = $addService;

        return $new;
    }

    /**
     * @return int
     */
    public function getMessageNumber()
    {
        return $this->messageNumber;
    }

    /**
     * @param int $messageNumber
     * @return Parcel
     */
    public function withMessageNumber($messageNumber)
    {
        $new = clone $this;
        $new->messageNumber = $messageNumber;

        return $new;
    }

    /**
     * @return string
     */
    public function getFunction()
    {
        return $this->function;
    }

    /**
     * @param string $function
     * @return Parcel
     */
    public function withFunction($function)
    {
        $new = clone $this;
        $new->function = $function;

        return $new;
    }

    /**
     * @return string
     */
    public function getParameter()
    {
        return $this->parameter;
    }

    /**
     * @param string $parameter
     * @return Parcel
     */
    public function withParameter($parameter)
    {
        $new = clone $this;
        $new->parameter = $parameter;

        return $new;
    }

    /**
     * @return \dpd\Type\Hazardous
     */
    public function getHazardous()
    {
        return $this->hazardous;
    }

    /**
     * @param \dpd\Type\Hazardous $hazardous
     * @return Parcel
     */
    public function withHazardous($hazardous)
    {
        $new = clone $this;
        $new->hazardous = $hazardous;

        return $new;
    }

    /**
     * @return bool
     */
    public function getPrintInfo1OnParcelLabel()
    {
        return $this->printInfo1OnParcelLabel;
    }

    /**
     * @param bool $printInfo1OnParcelLabel
     * @return Parcel
     */
    public function withPrintInfo1OnParcelLabel($printInfo1OnParcelLabel)
    {
        $new = clone $this;
        $new->printInfo1OnParcelLabel = $printInfo1OnParcelLabel;

        return $new;
    }

    /**
     * @return string
     */
    public function getInfo1()
    {
        return $this->info1;
    }

    /**
     * @param string $info1
     * @return Parcel
     */
    public function withInfo1($info1)
    {
        $new = clone $this;
        $new->info1 = $info1;

        return $new;
    }

    /**
     * @return string
     */
    public function getInfo2()
    {
        return $this->info2;
    }

    /**
     * @param string $info2
     * @return Parcel
     */
    public function withInfo2($info2)
    {
        $new = clone $this;
        $new->info2 = $info2;

        return $new;
    }

    /**
     * @return bool
     */
    public function getReturns()
    {
        return $this->returns;
    }

    /**
     * @param bool $returns
     * @return Parcel
     */
    public function withReturns($returns)
    {
        $new = clone $this;
        $new->returns = $returns;

        return $new;
    }

    /**
     * @return string
     */
    public function getParcelClass()
    {
        return $this->parcelClass;
    }

    /**
     * @param string $parcelClass
     * @return Parcel
     */
    public function withParcelClass($parcelClass)
    {
        $new = clone $this;
        $new->parcelClass = $parcelClass;

        return $new;
    }


}

