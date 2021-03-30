<?php

namespace dpd\Type;

use Phpro\SoapClient\Type\ResultInterface;

class GetAuthResponse implements ResultInterface
{

    /**
     * @var \dpd\Type\Login
     */
    private $return;

    /**
     * @return \dpd\Type\Login
     */
    public function getReturn()
    {
        return $this->return;
    }

    /**
     * @param \dpd\Type\Login $return
     * @return GetAuthResponse
     */
    public function withReturn($return)
    {
        $new = clone $this;
        $new->return = $return;

        return $new;
    }
}
