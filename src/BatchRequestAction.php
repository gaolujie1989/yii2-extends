<?php
/**
 * @copyright Copyright (c) 2017
 */

namespace lujie\batch;

use Yii;
use yii\base\InvalidConfigException;
use yii\rest\Action;
use yii\web\Response;

/**
 * Class BatchAction
 *
 * @package lujie\core\rest
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class BatchRequestAction extends Action
{
    /**
     * @return array
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function run(): void
    {
        $result = [];
        $request = Yii::$app->getRequest();
        $bodyParams = $request->getBodyParams();

        $response = Yii::$app->getResponse();
        $components = Yii::$app->getComponents();
        $responseConfig = $components['response'];

        foreach ($bodyParams as $key => $bodyParam) {
            $id = $bodyParam['id'] ?? $key;
            if (empty($bodyParam['route'])) {
                $result[$id] = [
                    'id' => $id,
                    'message' => 'Empty Route',
                    'status' => 404,
                ];
                continue;
            }
            $route = $bodyParam['route'];
            $params = $bodyParam['params'] ?? [];
            $request->setBodyParams($params);
            try {
                Yii::$app->set('response', $responseConfig);
                Yii::$app->runAction($route, $params);
                $actionResponse = Yii::$app->getResponse();
                $actionResponse->trigger(Response::EVENT_BEFORE_SEND);
                $result[$id] = [
                    'id' => $id,
                    'data' => $actionResponse->data,
                    'code' => $actionResponse->statusCode,
                    'status' => $actionResponse->statusCode,
                ];
            } catch (\Exception $e) {
                $result[$id] = [
                    'id' => $id,
                    'message' => $e->getMessage(),
                    'code' => $e->getCode(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTrace(),
                    'previous' => $e->getPrevious(),
                    'status' => 500,
                ];
            }
        }
        Yii::$app->set('response', $response);
        $response->statusCode = 200;
        return $result;
    }
}
