#!/usr/bin/env php
<?php
/**
 * @copyright Copyright (c) 2019
 */

use lujie\workerman\FileMonitor;
use lujie\workerman\Yii2WebServer;
use Workerman\Worker;

defined('YII_APP_BASE_PATH') or define('YII_APP_BASE_PATH', '/app/apps');
defined('YII_ENABLE_ERROR_HANDLER') or define('YII_ENABLE_ERROR_HANDLER', false);
defined('WORKERMAN_UPLOAD_FILENAME_PREFIX') or define('WORKERMAN_UPLOAD_FILENAME_PREFIX', 'wkm_upd_');
defined('XHGUI_BASH_PATH') or define('XHGUI_BASH_PATH', '/app/devtools/xhgui-branch');

require_once YII_APP_BASE_PATH . '/vendor/autoload.php';

Worker::$logFile = '/var/log/workerman.log';
Worker::$pidFile = '/var/run/workerman.pid';

$webServer = new Yii2WebServer('http://0.0.0.0:8080');
$webServer->name = 'Yii2WebApp';
$webServer->user = 'www-data';
$webServer->group = 'www-data';
$webServer->count = 4;
$webServer->addRoot('web', YII_APP_BASE_PATH . '/web/');

$fileMonitor = new FileMonitor();
$fileMonitor->monitorDir = '/app';
$fileMonitorWorker = new Worker();
$fileMonitorWorker->name = 'FileMonitor';
$fileMonitorWorker->reloadable = false;
$fileMonitorWorker->onWorkerStart = [$fileMonitor, 'startFileMonitoring'];

Worker::runAll();
