<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\workerman;

use Workerman\Connection\TcpConnection;
use Workerman\Protocols\Http\Response;

/**
 * Class Http
 * @package lujie\workerman
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class WebHttp extends \Workerman\Protocols\Http
{
    /**
     * @param TcpConnection $connection
     * @param Response $response
     * @inheritdoc
     */
    public static function sendResponse(TcpConnection $connection, Response $response): void
    {
        $connection->send($response);
        if ($response instanceof WebResponse && $stream = $response->rawStream()) {
            if (is_callable($stream)) {
                $data = $stream();
                foreach ($data as $datum) {
                    $connection->send($datum, true);
                }
                return;
            }

            if (is_array($stream)) {
                [$handle, $begin, $end] = $stream;
                static::sendStream($connection, $handle, $begin, $end - $begin);
            } else {
                static::sendStream($connection, $stream);
            }
        }
    }
}