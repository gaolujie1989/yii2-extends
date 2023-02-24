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

/**
 * Class Console
 * @package lujie\extend\web
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class Console extends BaseObject
{
    /**
     * @var DataStorageInterface
     */
    public $dataStorage = [
        'class' => FileDataStorage::class,
        'file' => '@runtime/WebConsole.php'
    ];

    /**
     * @var string
     */
    public $triggerUrl = '';

    public $daemonCount = 2;

    public $daemonInterval = 5;

    public $workerCount = 4;

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
    public function daemon(): void
    {
        ignore_user_abort(true);
        set_time_limit(0);
        $pid = getmypid();
        while (true) {
            $daemons = $this->dataStorage->get('daemons');
            foreach ($daemons as $daemon) {

            }
        }
    }

    public function work(): void
    {

    }
}