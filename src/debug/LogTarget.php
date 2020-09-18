<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\debug;


use Yii;

/**
 * Class LogTarget
 * @package lujie\extend\debug
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class LogTarget extends \yii\debug\LogTarget
{
    /**
     * Collects summary data of current request.
     * @return array
     */
    protected function collectSummary()
    {
        $app = Yii::$app;
        if ($app === null) {
            return [];
        }

        if ($app instanceof yii\web\Application) {
            return parent::collectSummary();
        }

        if ($app instanceof yii\console\Application) {
            /** @var yii\console\Request $request */
            $request = Yii::$app->getRequest();
            /** @var yii\console\Response $response */
            $response = Yii::$app->getResponse();
            $summary = [
                'tag' => $this->tag,
                'url' => implode(' ', $request->getParams()),
                'ajax' => 0,
                'method' => 'CONSOLE',
                'ip' => 'local',
                'time' => $_SERVER['REQUEST_TIME_FLOAT'],
                'statusCode' => $response->exitStatus,
                'sqlCount' => $this->getSqlTotalCount(),
            ];

            if (isset($this->module->panels['mail'])) {
                $mailFiles = $this->module->panels['mail']->getMessagesFileName();
                $summary['mailCount'] = count($mailFiles);
                $summary['mailFiles'] = $mailFiles;
            }
            return $summary;
        }

        return [];
    }
}