<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\workerman\handlers;

use lujie\workerman\db\Command;
use lujie\workerman\db\Connection;
use lujie\workerman\log\Logger;
use lujie\workerman\web\ErrorHandler;
use Workerman\Protocols\Http\Request;
use Workerman\Protocols\Http\Response;
use Yii;
use yii\base\InvalidConfigException;
use yii\web\Application;

defined('YII_ENABLE_ERROR_HANDLER') or define('YII_ENABLE_ERROR_HANDLER', false);

/**
 * Class RequestHandler
 * @package lujie\workerman\handlers
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class Yii2RequestHandler implements RequestHandlerInterface
{
    /**
     * @var Application
     */
    private $yii2App;

    /**
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function init(): void
    {
        $yiiDir = dirname(__DIR__) . '/yii/';
        include_once $yiiDir . '/rewrite_classes.php';
        include_once $yiiDir . '/rewrite_functions.php';

        $this->yii2App = include $_SERVER['SCRIPT_FILENAME'];
        $app = $this->yii2App;
        $app->getErrorHandler()->unregister();
        foreach ($app->getComponents() as $name => $config) {
            if ($name === 'logger') {
                $config['class'] = Logger::class;
                $app->setComponents([$name => $config]);
            }
            if ($name === 'errorHandler') {
                $config['class'] = ErrorHandler::class;
                $app->setComponents([$name => $config]);
            }
            if (ltrim($config['class'], '\\') === \yii\db\Connection::class) {
                $config['class'] = Connection::class;
                $config['commandClass'] = Command::class;
                $app->setComponents([$name => $config]);
            }
            if (ltrim($config['class'], '\\') === \yii\web\Response::class) {
                $config['class'] = \lujie\workerman\web\Response::class;
                $app->setComponents([$name => $config]);
            }
            $app->get($name);
        }
        foreach ($app->getModules() as $name => $config) {
            $app->getModule($name);
        }
        $app->getErrorHandler()->register();
    }

    /**
     * @param Request $request
     * @return Response
     * @inheritdoc
     */
    public function handle(Request $request): Response
    {
        $app = $this->yii2App;
        try {
            $componentsConfig = $app->getComponents();
            $app->set('request', $componentsConfig['request']);
            $app->set('response', $componentsConfig['response']);
            $app->getRequest()->setRawBody($request->rawBody());
            $app->run();
        } catch (\Throwable $error) {
            $app->getErrorHandler()->handleException($error);
        }
        Yii::getLogger()->flush(true);

        /** @var \lujie\workerman\web\Response $response */
        $response = $app->getResponse();
        return $response->getWorkermanResponse();
    }
}