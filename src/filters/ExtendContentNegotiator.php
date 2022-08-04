<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\filters;

use yii\helpers\ArrayHelper;

/**
 * Class ContentNegotiator
 * @package lujie\extend\filters\auth
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ExtendContentNegotiator extends \yii\filters\ContentNegotiator
{
    /**
     * @param \yii\web\Request $request
     * @param \yii\web\Response $response
     * @throws \yii\web\BadRequestHttpException
     * @throws \yii\web\NotAcceptableHttpException
     * @throws \Exception
     * @inheritdoc
     */
    protected function negotiateContentType($request, $response): void
    {
        if (strpos($this->formatParam, '.') !== false) {
            $formatValue = ArrayHelper::getValue($request, $this->formatParam);
            if ($formatValue) {
                $oldQueryParams = $request->getQueryParams();
                $request->setQueryParams(array_merge($oldQueryParams, [$this->formatParam => $formatValue]));
            }
        }
        parent::negotiateContentType($request, $response);
        if (isset($oldQueryParams)) {
            $request->setQueryParams($oldQueryParams);
        }
    }

    /**
     * @param \yii\web\Request $request
     * @throws \Exception
     * @inheritdoc
     */
    protected function negotiateLanguage($request): void
    {
        if (strpos($this->languageParam, '.') !== false) {
            $languageValue = ArrayHelper::getValue($request, $this->languageParam);
            if ($languageValue) {
                $oldQueryParams = $request->getQueryParams();
                $request->setQueryParams(array_merge($oldQueryParams, [$this->languageParam => $languageValue]));
            }
        }
        parent::negotiateLanguage($request);
        if (isset($oldQueryParams)) {
            $request->setQueryParams($oldQueryParams);
        }
    }
}