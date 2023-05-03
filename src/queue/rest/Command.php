<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\queue\rest;

use lujie\extend\helpers\HttpClientHelper;
use lujie\extend\queue\ExecForm;
use lujie\extend\rest\MethodAction;
use Yii;
use yii\authclient\InvalidResponseException;
use yii\di\Instance;
use yii\httpclient\Client;
use yii\httpclient\CurlTransport;
use yii\httpclient\Exception;
use yii\queue\cli\Queue;
use yii\queue\ExecEvent;
use yii\rest\Controller;
use yii\web\NotFoundHttpException;

/**
 * Base Command.
 *
 * @author Roman Zhuravlev <zhuravljov@gmail.com>
 */
abstract class Command extends Controller
{
    public const EXEC_DONE = 0;

    public const EXEC_RETRY = 3;

    /**
     * @var Queue
     */
    public $queue;

    public $isolate = true;

    /**
     * @var Client
     */
    public $httpClient = [
        'class' => Client::class,
        'transport' => CurlTransport::class,
        'requestConfig' => [
            'format' => 'json'
        ],
    ];

    /**
     * @param string $actionID
     * @return bool
     * @inheritdoc
     */
    protected function isWorkerAction(string $actionID): bool
    {
        return in_array($actionID, ['run', 'listen'], true);
    }

    /**
     * @param string $actionID
     * @return bool
     * @inheritdoc
     */
    protected function canIsolate(string $actionID): bool
    {
        return $this->isWorkerAction($actionID);
    }

    /**
     * @param \yii\base\Action $action
     * @return bool
     * @throws \yii\web\BadRequestHttpException
     * @inheritdoc
     */
    public function beforeAction($action): bool
    {
        if ($this->isolate && $this->canIsolate($action->id)) {
            $this->queue->messageHandler = function ($id, $message, $ttr, $attempt) {
                return $this->handleMessage($id, $message, $ttr, $attempt);
            };
        }

        return parent::beforeAction($action);
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function actions(): array
    {
        return array_merge(parent::actions(), [
            'exec' => [
                'method' => MethodAction::class,
                'modelClass' => ExecForm::class,
                'queue' => $this->queue,
            ],
        ]);
    }

    /**
     * Handles message use new request.
     *
     * @param string|null $id of a message
     * @param string $message
     * @param int $ttr time to reserve
     * @param int $attempt number
     * @return bool
     * @throws
     * @see actionExec()
     */
    protected function handleMessage($id, string $message, int $ttr, int $attempt): bool
    {
        $url = Yii::$app->getRequest()->getHostInfo() . '/queue/exec';
        $this->httpClient = Instance::ensure($this->httpClient, Client::class);
        $request = $this->httpClient->createRequest();
        $request->setFullUrl($url)
            ->setMethod('POST')
            ->setData([
                'id' => $id,
                'ttr' => $ttr,
                'attempt' => $attempt,
                'message' => $message,
                'pid' => $this->queue->getWorkerPid() ?: 0,
            ])
            ->addOptions(['timeout' => $ttr]);

        try {
            $response = HttpClientHelper::sendRequest($request);
            $responseData = $response->getData();
            return (int)$responseData['data']['result'] === self::EXEC_DONE;
        } catch (Exception|InvalidResponseException $error) {
            [$job] = $this->queue->unserializeMessage($message);
            return $this->queue->handleError(new ExecEvent([
                'id' => $id,
                'job' => $job,
                'ttr' => $ttr,
                'attempt' => $attempt,
                'error' => $error,
            ]));
        }
    }

    public function actionRun(): void
    {
        ignore_user_abort(true);
        set_time_limit(0);
        $this->queue->run(false);
    }

    /**
     * @param int $timeout
     * @throws Exception
     * @inheritdoc
     */
    public function actionListen(int $timeout = 3): void
    {
        if (!is_numeric($timeout)) {
            throw new Exception('Timeout must be numeric.');
        }
        if ($timeout < 1) {
            throw new Exception('Timeout must be greater than zero.');
        }
        ignore_user_abort(true);
        set_time_limit(0);
        $this->queue->run(true, $timeout);
    }

    public function actionClear(): void
    {
        $this->queue->clear();
    }

    /**
     * @param $id
     * @throws NotFoundHttpException
     * @inheritdoc
     */
    public function actionRemove($id): void
    {
        if (!$this->queue->remove($id)) {
            throw new NotFoundHttpException('The job is not found.');
        }
    }
}
