<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\web;

use lujie\data\storage\DataStorageInterface;
use lujie\data\storage\FileDataStorage;
use Yii;
use yii\base\BaseObject;
use yii\di\Instance;
use yii\helpers\ArrayHelper;

/**
 * Class Console
 * @package lujie\extend\web
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class WebConsoleDaemon extends BaseObject
{
    /**
     * @var DataStorageInterface
     */
    public $dataStorage = [
        'class' => FileDataStorage::class,
        'file' => '@runtime/WebConsole.php'
    ];

    public $daemonCount = 2;

    public $daemonCheckInterval = 5;

    public $diedAfter = 300;

    /**
     * @var array
     * [ 'key' => ['url' => '', 'num' => '']
     */
    public $workers = [];

    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        $this->dataStorage = Instance::ensure($this->dataStorage, DataStorageInterface::class);
    }

    /**
     * @inheritdoc
     */
    public function daemonize(): void
    {
        ignore_user_abort(true);
        set_time_limit(0);
        $myPid = getmypid();
        while (true) {
            $now = time();
            $daemons = $this->dataStorage->get('daemons');

            $daemons = ArrayHelper::index($daemons, 'pid');
            foreach ($daemons as $pid => $daemon) {
                if ($myPid === $pid) {
                    continue;
                }
                if ($now - $daemon['pingedAt'] > $this->diedAfter) {
                    $this->dataStorage->remove($pid);
                    unset($daemons[$pid]);
                }
            }

            $myDaemon = $daemons[$myPid] ?? ['pid' => $myPid];
            $myDaemon['pingedAt'] = $now;
            $this->dataStorage->set($myPid, $myDaemon);
            $daemons[$myPid] = $myDaemon;

            while (count($daemons) < $this->daemonCount) {
                $url = Yii::$app->getRequest()->getHostInfo() . '/queue/exec';
            }
        }
    }

    public function work(): void
    {

    }
}