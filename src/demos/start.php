#!/usr/bin/env php
<?php
/**
 * @copyright Copyright (c) 2019
 */
// phpcs:ignoreFile

use lujie\workerman\FileMonitor;
use lujie\workerman\handlers\Yii2RequestHandler;
use lujie\workerman\WebServer;
use Workerman\Worker;

defined('YII_APP_BASE_PATH') or define('YII_APP_BASE_PATH', '/app/apps');

require YII_APP_BASE_PATH . '/vendor/autoload.php';

Worker::$logFile = '/var/log/workerman.log';
Worker::$pidFile = '/var/run/workerman.pid';

if (isset($_ENV['ENABLE_FILE_MONITOR'])) {
    $fileMonitor = new FileMonitor();
    $fileMonitor->checkInterval = $_ENV['FILE_MONITOR_INTERVAL'] ?? 5;
    $fileMonitor->monitorDirs = ['/app'];
    $fileMonitorWorker = new Worker();
    $fileMonitorWorker->name = 'FileMonitor';
    $fileMonitorWorker->reloadable = false;
    $fileMonitorWorker->onWorkerStart = [$fileMonitor, 'startFileMonitoring'];
}

$webServer = new WebServer('http://0.0.0.0:8080');
$webServer->name = 'Yii2WebApp';
$webServer->user = 'www-data';
$webServer->group = 'www-data';
$webServer->count = 4;
$webServer->addRoot('default', YII_APP_BASE_PATH . '/web/', new Yii2RequestHandler());
Worker::runAll();
