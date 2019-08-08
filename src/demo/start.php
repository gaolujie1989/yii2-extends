<?php
/**
 * @copyright Copyright (c) 2019
 */

use Workerman\WebServer;
use Workerman\Worker;

defined('YII_APP_BASE_PATH') or define('YII_APP_BASE_PATH', __DIR__ . '/../../../apps');
defined('YII_ENABLE_ERROR_HANDLER') or define('YII_ENABLE_ERROR_HANDLER', false);
defined('WORKERMAN_UPLOAD_FILENAME_PREFIX') or define('WORKERMAN_UPLOAD_FILENAME_PREFIX', 'wkm_upd');

require_once YII_APP_BASE_PATH . '/vendor/autoload.php';

$webServer = new WebServer('http://0.0.0.0:8080');

$webServer->addRoot('web', YII_APP_BASE_PATH . '/web/');

$webServer->count = 4;

Worker::runAll();
