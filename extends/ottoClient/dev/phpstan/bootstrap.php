<?php
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'test');
defined('YII_APP_BASE_PATH') or define('YII_APP_BASE_PATH', __DIR__ . '/../../../../apps');

require_once YII_APP_BASE_PATH . '/vendor/autoload.php';
require_once YII_APP_BASE_PATH . '/vendor/yiisoft/yii2/Yii.php';
require_once YII_APP_BASE_PATH . '/common/config/bootstrap.php';

Yii::setAlias('@tests', __DIR__);

use yii\console\Application;
use yii\helpers\ArrayHelper;

$config = ArrayHelper::merge(
    require YII_APP_BASE_PATH . '/common/config/main.php',
    require YII_APP_BASE_PATH . '/common/config/main-local.php',
    require YII_APP_BASE_PATH . '/common/config/test.php',
    require YII_APP_BASE_PATH . '/common/config/test-local.php',
    require YII_APP_BASE_PATH . '/console/config/main.php',
    require YII_APP_BASE_PATH . '/console/config/main-local.php',
    require YII_APP_BASE_PATH . '/console/config/test.php',
    require YII_APP_BASE_PATH . '/console/config/test-local.php',
    ['class' => Application::class]
);
return $config;
