<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\authclient;


class JsonRpcResponse
{
    /**
     * @var bool
     */
    public $success;

    /**
     * @var string
     */
    public $message;

    /**
     * @var array
     */
    public $data;

    /**
     * @var array
     */
    public $errors;
}