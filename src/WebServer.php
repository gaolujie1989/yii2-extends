<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\workerman;

use lujie\workerman\handlers\RequestHandler;
use lujie\workerman\handlers\RequestHandlerInterface;
use Workerman\Connection\TcpConnection;
use Workerman\Protocols\Http\Request;
use Workerman\Worker;

/**
 * Class Yii2WebServer
 * @package lujie\workerman
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class WebServer extends Worker
{
    /**
     * @var string
     */
    public $name = 'WebServer';

    /**
     * Virtual host config
     * [
     *      'workerman.net' => [
     *          'root' => '/wwwroot/workerman',
     *          'handler' => new RequestHandler()
     *      ],
     * ]
     *
     * @var array
     */
    protected $serverRoots = [];

    /**
     * @param string $domain
     * @param string $rootPath
     * @inheritdoc
     */
    public function addRoot(string $domain, string $rootPath, ?RequestHandlerInterface $handler = null): void
    {
        $this->serverRoots[$domain] = [
            'root' => $rootPath,
            'handler' => $handler,
        ];
    }

    #region Restart after max request

    /**
     * @var int
     */
    private $currentRequestCount = 0;

    /**
     * @var int
     */
    public $maxRequestCount = 10000;

    /**
     * @inheritdoc
     */
    protected function restartAfterMaxRequest(): void
    {
        if ($this->maxRequestCount && ++$this->currentRequestCount > $this->maxRequestCount) {
            Worker::stopAll();
        }
    }

    #endregion

    /**
     * Run webserver instance.
     *
     * @see Workerman.Worker::run()
     */
    public function run(): void
    {
        $this->onWorkerStart = [$this, 'onWorkerStartCallback'];
        $this->onMessage = [$this, 'onMessageCallback'];
        parent::run();
    }

    /**
     * @inheritdoc
     */
    public function onWorkerStartCallback(): void
    {
        if (empty($this->serverRoots)) {
            Worker::safeEcho(new \Exception('server root not set, please use WebServer::addRoot($domain, $rootPath) to set server root path'));
            exit(250);
        }
    }

    /**
     * @param TcpConnection $connection
     * @param Request $request
     * @inheritdoc
     */
    public function onMessageCallback(TcpConnection $connection, Request $request): void
    {
        $this->buildGlobalVars($connection, $request);

        $workermanRoot = getcwd();
        chdir($_SERVER['DOCUMENT_ROOT']);

        $serverRoot = $this->getServerRoot($request);
        $requestHandler = $serverRoot['handler'] ?: new RequestHandler();
        $response = $requestHandler->handle($request);

        chdir($workermanRoot);
        $connection->send($response);

        $this->restartAfterMaxRequest();
    }

    /**
     * @param TcpConnection $connection
     * @param Request $request
     * @inheritdoc
     */
    protected function buildGlobalVars(TcpConnection $connection, Request $request): void
    {
        $_GET = $request->get();
        $_POST = $request->post();
        $_COOKIE = $request->cookie();
        $_REQUEST = array_merge($_GET, $_POST);
        $_FILES = $request->file();
        $header = $request->header();
        foreach ($header as $name => $value) {
            $_SERVER['HTTP_' . strtoupper($name)] = $value;
        }
        $_SERVER['REQUEST_METHOD'] = $request->method();
        $_SERVER['REQUEST_URI'] = $request->path();
        $_SERVER['QUERY_STRING'] = $request->queryString();
        $_SERVER['REMOTE_ADDR'] = $connection->getRemoteIp();
        $_SERVER['REMOTE_PORT'] = $connection->getRemotePort();
        $_SERVER['REQUEST_TIME_FLOAT'] = microtime(true);
        $_SERVER['REQUEST_TIME'] = time();

        $serverRoot = $this->getServerRoot($request);
        $_SERVER['DOCUMENT_ROOT'] = $serverRoot['root'];
        $_SERVER['SCRIPT_NAME'] = 'index.php';
        $_SERVER['SCRIPT_FILENAME'] = $serverRoot['root'] . '/index.php';
        $_SERVER['PHP_SELF'] = '/index.php';
    }

    /**
     * @param Request $request
     * @return array
     * @inheritdoc
     */
    protected function getServerRoot(Request $request): array
    {
        $domain = $request->host(true);
        return $this->serverRoots[$domain] ?? reset($this->serverRoots);
    }
}
