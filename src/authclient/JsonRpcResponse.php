<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\authclient;

/**
 * Class JsonRpcResponse
 * @package lujie\extend\authclient
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
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

    /**
     * @param bool $throwException
     * @return array|null
     * @throws JsonRpcException
     * @inheritdoc
     */
    public function getData(bool $throwException = true): ?array
    {
        if (!$this->success) {
            if ($throwException) {
                throw new JsonRpcException($this, 'JsonRpc error');
            }
            return null;
        }
        return $this->data;
    }
}