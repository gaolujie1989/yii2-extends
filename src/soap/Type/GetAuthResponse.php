<?php

namespace lujie\dpd\soap\Type;

use yii\base\BaseObject;
use Phpro\SoapClient\Type\ResultInterface;

class GetAuthResponse extends BaseObject implements ResultInterface
{

    /**
     * @var \lujie\dpd\soap\Type\Login
     */
    private $return;

    /**
     * @return \lujie\dpd\soap\Type\Login
     */
    public function getReturn()
    {
        return $this->return;
    }

    /**
     * @param \lujie\dpd\soap\Type\Login $return
     * @return $this
     */
    public function setReturn($return) : \lujie\dpd\soap\Type\GetAuthResponse
    {
        $this->return = $return;
        return $this;
    }

    /**
     * @param \lujie\dpd\soap\Type\Login $return
     * @return GetAuthResponse
     */
    public function withReturn(\lujie\dpd\soap\Type\Login $return) : \lujie\dpd\soap\Type\GetAuthResponse
    {
        $new = clone $this;
        $new->return = $return;

        return $new;
    }


}

