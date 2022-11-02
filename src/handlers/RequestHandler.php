<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\workerman\handlers;

use Workerman\Protocols\Http\Request;
use Workerman\Protocols\Http\Response;

/**
 * Class RequestHandler
 * @package lujie\workerman\handlers
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class RequestHandler implements RequestHandlerInterface
{
    public function init(): void
    {
    }

    public function handle(Request $request): Response
    {
        $response = new Response();
        $file = $_SERVER['DOCUMENT_ROOT'] . '/' . $request->path();
        if (!is_file($file)) {
            $file = $_SERVER['SCRIPT_FILENAME'];
        }
        if (strtolower(pathinfo($file, PATHINFO_EXTENSION)) === 'php') {
            ob_start();
            $content = require $file;
            $output = ob_get_clean() ?: '';
            $response->withBody(is_string($content) ? $content : $output);
        } else {
            $response->withFile($file);
        }
        return $response;
    }
}