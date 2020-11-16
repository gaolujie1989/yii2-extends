<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\authclient;

use yii\authclient\InvalidResponseException;
use yii\base\Exception;
use yii\base\InvalidCallException;

/**
 * Class JsonRpcResponse
 * @package lujie\extend\authclient
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class JsonRpcException extends Exception
{
    /**
     * @var JsonRpcResponse
     */
    public $response;

    /**
     * JsonRpcException constructor.
     * @param $response
     * @param null $message
     * @param int $code
     * @param \Exception|null $previous
     */
    public function __construct($response, $message = null, $code = 0, \Exception $previous = null)
    {
        $this->response = $response;
        parent::__construct($message, $code, $previous);
    }
}