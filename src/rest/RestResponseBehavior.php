<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\rest;

use yii\base\Behavior;
use yii\base\Event;
use yii\filters\Cors;
use yii\web\Response;

/**
 * Class RestResponseBehavior
 * @package lujie\core\rest
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class RestResponseBehavior extends Behavior
{
    /**
     * @var bool
     */
    public $enableCors = false;

    /**
     * @var bool
     */
    public $alwaysStatusOK = false;

    /**
     * @return array
     * @inheritdoc
     */
    public function events(): array
    {
        return [
            Response::EVENT_BEFORE_SEND => 'beforeSend',
        ];
    }

    /**
     * @param Event $event
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function beforeSend(Event $event): void
    {
        /** @var \yii\web\Response $response */
        $response = $event->sender;
        $data = $response->data;

        if ($response->format === $response::FORMAT_JSON) {
            //Exception will be handle by error handler, model errors will be handle by rest Serializer
            if ($response->statusCode >= 200 && $response->statusCode < 300) {
                $response->data = [
                    'data' => $data,
                    'code' => $response->statusCode,
                    'status' => $response->statusCode,
                ];
                $response->statusCode = 200;
            } else if ($response->statusCode === 422) {  //if model return errors
                $firstError = reset($data);
                $response->data = [
                    'message' => $firstError['message'] ?: 'Data Validation Failed.',
                    'errors' => $data,
                    'code' => $response->statusCode,
                    'status' => $response->statusCode,
                ];
                $response->statusCode = 200;
            } else {
                $response->data['status'] = $response->statusCode;
                $response->data['code'] = $response->statusCode;
                if ($this->alwaysStatusOK) {
                    $response->statusCode = 200;
                }
            }

            if ($this->enableCors) {
                $cors = new Cors();
                $requestCorsHeaders = $cors->extractHeaders();
                $responseCorsHeaders = $cors->prepareHeaders($requestCorsHeaders);
                $cors->addCorsHeaders($response, $responseCorsHeaders);
            }
        }
    }
}
