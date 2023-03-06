<?php

use yii\helpers\ArrayHelper;
use yii\web\Application;

$config = ArrayHelper::merge(
    require YII_APP_BASE_PATH . '/common/config/main.php',
    require YII_APP_BASE_PATH . '/common/config/main-local.php',
    require YII_APP_BASE_PATH . '/common/config/test.php',
    require YII_APP_BASE_PATH . '/common/config/test-local.php',
    require YII_APP_BASE_PATH . '/backend/config/main.php',
    require YII_APP_BASE_PATH . '/backend/config/main-local.php',
    require YII_APP_BASE_PATH . '/backend/config/test.php',
    require YII_APP_BASE_PATH . '/backend/config/test-local.php',
    ['class' => Application::class]
);
unset($config['as restAccessControl'], $config['as authAccessControl']);
return $config;
