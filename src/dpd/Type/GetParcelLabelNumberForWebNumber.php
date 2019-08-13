<?php

namespace dpd\Type;

use Phpro\SoapClient\Type\RequestInterface;

class GetParcelLabelNumberForWebNumber implements RequestInterface
{

    /**
     * @var string
     */
    private $webNumber;

    /**
     * Constructor
     *
     * @var string $webNumber
     */
    public function __construct($webNumber)
    {
        $this->webNumber = $webNumber;
    }

    /**
     * @return string
     */
    public function getWebNumber()
    {
        return $this->webNumber;
    }

    /**
     * @param string $webNumber
     * @return GetParcelLabelNumberForWebNumber
     */
    public function withWebNumber($webNumber)
    {
        $new = clone $this;
        $new->webNumber = $webNumber;

        return $new;
    }


}

