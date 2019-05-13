<?php
/**
 * @copyright Copyright (c) 2017
 */

namespace lujie\batch;

use Yii;
use yii\rest\Action;
use yii\rest\Serializer;

/**
 * Class BatchAction
 *
 * @package lujie\core\rest
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class BatchAction extends Action
{
    /**
     * @return array
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function run()
    {
        $result = [];
        $request = Yii::$app->getRequest();
        $response = Yii::$app->getResponse();
        $bodyParams = $request->getBodyParams();
        $this->controller->detachBehaviors();
        /** @var Serializer $serializer */
        $serializer = Yii::createObject($this->controller->serializer);
        foreach ($bodyParams as $key => $bodyParam) {
            if (!isset($bodyParam['action'])) {
                continue;
            }
            $action = $bodyParam['action'];
            $id = isset($bodyParam['id']) ? $bodyParam['id'] : $key;
            $params = isset($bodyParam['params']) ? $bodyParam['params'] : [];
            $request->setBodyParams($params);
            try {
                $data = $this->controller->runAction($action, $params);
                $data = $serializer->serialize($data);

                if ($response->statusCode >= 200 && $response->statusCode < 300) {
                    $result[] = [
                        'id' => $id,
                        'data' => $data,
                        'code' => $response->statusCode,
                        'status' => $response->statusCode,
                    ];
                    $response->statusCode = 200;
                } else if ($response->statusCode == 422) {  //if model return errors
                    $firstError = reset($data);
                    $result[] = [
                        'id' => $id,
                        'message' => $firstError['message'],
                        'errors' => $data,
                        'code' => $response->statusCode,
                        'status' => $response->statusCode,
                    ];
                    $response->statusCode = 200;
                } else {
                    $result[] = [
                        'id' => $id,
                        'data' => $data,
                        'code' => $response->statusCode,
                        'status' => $response->statusCode,
                    ];
                }
            } catch (\Exception $e) {
                $result[] = [
                    'id' => $id,
                    'message' => $e->getMessage(),
                    'code' => $e->getCode(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTrace(),
                    'previous' => $e->getPrevious(),
                    'status' => $response->statusCode,
                ];
            }
        }
        $this->controller->attachBehaviors($this->controller->behaviors());
        $response->statusCode = 200;
        return $result;
    }
}
