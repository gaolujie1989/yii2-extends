<?php
/**
 * @copyright Copyright (c) 2023
 */

namespace lujie\extend\httpclient;

use yii\helpers\Json;
use yii\httpclient\Request;

/**
 * Class JsonFormatter
 * @package lujie\extend\httpclient
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class JsonFormatter extends \yii\httpclient\JsonFormatter
{
    /**
     * @param Request $request
     * @return Request
     * @inheritdoc
     */
    public function format(Request $request): Request
    {
        if (!$request->getHeaders()->has('Content-Type')) {
            $request->getHeaders()->set('Content-Type', 'application/json; charset=UTF-8');
        }
        if (($data = $request->getData()) !== null) {
            $request->setContent(Json::encode($data, $this->encodeOptions));
        }
        return $request;
    }
}
