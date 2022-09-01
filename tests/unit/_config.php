<?php

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
$config['components']['dbArchived'] = $config['components']['db'];
return $config;
