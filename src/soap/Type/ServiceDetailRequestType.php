<?php

namespace lujie\dpd\soap\Type;

use yii\base\BaseObject;

class ServiceDetailRequestType extends BaseObject
{

    /**
     * @var string
     */
    private $code;

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param string $code
     * @return $this
     */
    public function setCode(string $code) : \lujie\dpd\soap\Type\ServiceDetailRequestType
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @param string $code
     * @return ServiceDetailRequestType
     */
    public function withCode(string $code) : \lujie\dpd\soap\Type\ServiceDetailRequestType
    {
        $new = clone $this;
        $new->code = $code;

        return $new;
    }


}

