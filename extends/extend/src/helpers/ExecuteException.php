<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\helpers;

use Throwable;
use yii\base\Exception;

/**
 * Class ExecuteException
 * @package lujie\extend\helpers
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ExecuteException extends Exception
{
    public $status;

    public $result = [];

    public function __construct($status, $result = [], $message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->status = $status;
        $this->result = $result;
    }

    /**
     * @return string
     * @inheritdoc
     */
    public function getName(): string
    {
        return 'Execute Exception';
    }
}