<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\debug;

use Yii;
use yii\console\Application as ConsoleApplication;
use yii\console\Request;
use yii\console\Response;
use yii\debug\panels\MailPanel;
use yii\web\Application as WebApplication;

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

        if ($app instanceof WebApplication) {
            return parent::collectSummary();
        }

        if ($app instanceof ConsoleApplication) {
            /** @var Request $request */
            $request = $app->getRequest();
            /** @var Response $response */
            $response = $app->getResponse();
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
                $mailPanel = $this->module->panels['mail'];
                if ($mailPanel instanceof MailPanel) {
                    $mailFiles = $mailPanel->getMessagesFileName();
                    $summary['mailCount'] = count($mailFiles);
                    $summary['mailFiles'] = $mailFiles;
                }
            }
            return $summary;
        }

        return [];
    }

    /**
     * @param array $messages
     * @param bool $final
     * @throws \yii\base\Exception
     * @inheritdoc
     */
    public function collect($messages, $final)
    {
        $this->messages = array_merge($this->messages, static::filterMessages($messages, $this->getLevels(), $this->categories, $this->except));
        if ($final) {
            $this->export();
        }
    }
}
