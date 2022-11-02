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
interface RequestHandlerInterface
{
    public function init(): void;

    public function handle(Request $request): Response;
}