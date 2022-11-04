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
     * @return array|null
     */
    public function rawStream(): ?array
    {
        return $this->stream;
    }

    public function __toString()
    {
        if (isset($this->stream)) {
            return $this->createHeadForStream();
        }
        return parent::__toString();
    }

    /**
     * @return string
     * @inheritdoc
     */
    protected function createHeadForStream(): string
    {
        $reason = $this->_reason ?: static::$_phrases[$this->_status];
        $head = "HTTP/{$this->_version} {$this->_status} $reason\r\n";
        $headers = $this->_header;
        if (!isset($headers['Server'])) {
            $head .= "Server: workerman\r\n";
        }
        foreach ($headers as $name => $value) {
            if (\is_array($value)) {
                foreach ($value as $item) {
                    $head .= "$name: $item\r\n";
                }
                continue;
            }
            $head .= "$name: $value\r\n";
        }

        if (!isset($headers['Connection'])) {
            $head .= "Connection: keep-alive\r\n";
        }

        if (!isset($headers['Content-Type'])) {
            $head .= "Content-Type: application/octet-stream\r\n";
        }

        return "{$head}\r\n";
    }
}