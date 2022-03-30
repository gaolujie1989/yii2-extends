#!/usr/bin/env php
<?php
/**
 * @copyright Copyright (c) 2019
 */
// phpcs:ignoreFile

use lujie\workerman\FileMonitor;
use lujie\workerman\Yii2WorkerHandler;
use Workerman\Worker;

defined('YII_APP_BASE_PATH') or define('YII_APP_BASE_PATH', '/app/apps');
defined('YII_ENABLE_ERROR_HANDLER') or define('YII_ENABLE_ERROR_HANDLER', false);
defined('WORKERMAN_UPLOAD_FILENAME_PREFIX') or define('WORKERMAN_UPLOAD_FILENAME_PREFIX', 'wkm_upd_');
defined('XHGUI_BASH_PATH') or define('XHGUI_BASH_PATH', '/app/devtools/xhgui-branch');
require_once YII_APP_BASE_PATH . '/vendor/autoload.php';
Worker::$logFile = '/var/log/workerman.log';
Worker::$pidFile = '/var/run/workerman.pid';
if (isset($_ENV['ENABLE_FILE_MONITOR'])) {
    $fileMonitor = new FileMonitor();
    $fileMonitor->checkInterval = $_ENV['FILE_MONITOR_INTERVAL'] ?? 2;
    $fileMonitor->monitorDirs = ['/app'];
    $fileMonitorWorker = new Worker();
    $fileMonitorWorker->name = 'FileMonitor';
    $fileMonitorWorker->reloadable = false;
    $fileMonitorWorker->onWorkerStart = [$fileMonitor, 'startFileMonitoring'];
}

$yii2WorkerHandler = new Yii2WorkerHandler();
$yii2WorkerHandler->serverRoot = [
    'web' => YII_APP_BASE_PATH . '/web/',
];
$yii2Worker = new Worker('http://0.0.0.0:8080');
$yii2Worker->name = 'Yii2WebApp';
$yii2Worker->user = 'www-data';
$yii2Worker->group = 'www-data';
$yii2Worker->count = 4;
$yii2Worker->onWorkerStart = [$yii2WorkerHandler, 'initYii2Apps'];
$yii2Worker->onMessage = [$yii2WorkerHandler, 'handleMessage'];
Worker::runAll();
