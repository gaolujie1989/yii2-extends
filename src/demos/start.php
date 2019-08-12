<?php
/**
 * @copyright Copyright (c) 2019
 */

use lujie\workerman\Yii2WebServer;

defined('YII_APP_BASE_PATH') or define('YII_APP_BASE_PATH', __DIR__ . '/../../../../apps');
defined('YII_ENABLE_ERROR_HANDLER') or define('YII_ENABLE_ERROR_HANDLER', false);
defined('WORKERMAN_UPLOAD_FILENAME_PREFIX') or define('WORKERMAN_UPLOAD_FILENAME_PREFIX', 'wkm_upd_');
defined('XHGUI_BASH_PATH') or define('XHGUI_BASH_PATH', __DIR__ . '/../../../../devtools/xhgui-branch');

require_once YII_APP_BASE_PATH . '/vendor/autoload.php';

$webServer::$logFile = __DIR__ . '/workerman.log';
$webServer::$pidFile = __DIR__ . '/workerman.pid';

$webServer = new Yii2WebServer('http://0.0.0.0:8080');
$webServer->addRoot('backend', YII_APP_BASE_PATH . '/backend/web/');
$webServer->user = 'www-data';
$webServer->group = 'www-data';
$webServer->count = 4;

Yii2WebServer::runAll();
