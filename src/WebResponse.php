<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\workerman;

use Workerman\Protocols\Http\Response;

/**
 * Class WebResponse
 * @package lujie\workerman
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class WebResponse extends Response
{
    /**
     * Send stream data
     *
     * @var array
     */
    public $stream = null;

    /**
     * Set stream
     * @param $stream
     * @return $this
     * @inheritdoc
     */
    public function withStream(array $stream): WebResponse
    {
        $this->stream = $stream;
        return $this;
    }

    /**
     * Get stream
     *
     * @return array
     */
    public function rawStream(): array
    {
        return $this->stream;
    }
}