<?php

namespace lujie\dpd\soap\Type;

use yii\base\BaseObject;

class ServiceDetailType extends BaseObject
{

    /**
     * @var string
     */
    private $code;

    /**
     * @var string
     */
    private $description;

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
    public function setCode(string $code) : \lujie\dpd\soap\Type\ServiceDetailType
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @param string $code
     * @return ServiceDetailType
     */
    public function withCode(string $code) : \lujie\dpd\soap\Type\ServiceDetailType
    {
        $new = clone $this;
        $new->code = $code;

        return $new;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return $this
     */
    public function setDescription(string $description) : \lujie\dpd\soap\Type\ServiceDetailType
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @param string $description
     * @return ServiceDetailType
     */
    public function withDescription(string $description) : \lujie\dpd\soap\Type\ServiceDetailType
    {
        $new = clone $this;
        $new->description = $description;

        return $new;
    }


}

