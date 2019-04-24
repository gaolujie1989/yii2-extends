<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\scheduling;

use Carbon\Carbon;
use Cron\CronExpression;
use Yii;
use yii\base\Arrayable;
use yii\base\BaseObject;
use yii\base\InvalidConfigException;
use yii\di\Instance;
use yii\mutex\Mutex;

/**
 * Class Task
 * @package lujie\scheduling
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class CronTask extends BaseObject implements TaskInterface, WithoutOverlappingTaskInterface, QueuedTaskInterface, Arrayable
{
    /**
     * @var array
     */
    public $data = [];

    /**
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        if (empty($this->data['taskCode'])) {
            throw new InvalidConfigException('Task code must be set');
        }
        if (empty($this->data['expression'])) {
            throw new InvalidConfigException('Task cron express must be set');
        }
    }

    #region TaskInterface

    public function getTaskCode()
    {
        return $this->data['taskCode'];
    }

    public function getTaskDescription()
    {
        return $this->data['taskDescription'] ?: '';
    }

    public function isDue()
    {
        $date = Carbon::now();
        if ($this->getTimezone()) {
            $date->setTimezone($this->getTimezone());
        }
        return CronExpression::factory($this->getExpression())->isDue($date->toDateTimeString());;
    }

    public function getNextRunTime()
    {
        $date = Carbon::now();
        if ($this->getTimezone()) {
            $date->setTimezone($this->getTimezone());
        }
        return CronExpression::factory($this->getExpression())->getNextRunDate($date->toDateTimeString());
    }

    public function getExpression()
    {
        return $this->data['expression'];
    }

    public function getTimezone()
    {
        return $this->data['timezone'] ?? Yii::$app->timeZone;
    }

    /**
     * @return bool|mixed
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function execute()
    {
        if (empty($this->data['callback'])) {
            return false;
        }
        $callback = $this->data['callback'];
        if (is_array($callback) && isset($callback['class'])) {
            $object = Yii::createObject($callback);
            $methods = ['run', 'execute', 'handle'];
            foreach ($methods as $method) {
                if (method_exists($object, $method)) {
                    return call_user_func([$object, $method]);
                }
            }
            throw new InvalidConfigException('Invalid callback method');
        } elseif (is_callable($callback)) {
            return call_user_func($callback);
        }
        return false;
    }

    #endregion

    #region WithoutOverlappingTaskInterface

    public function isWithoutOverlapping()
    {
        return $this->data['isWithoutOverlapping'] ?? false;
    }

    public function getExpiresAt()
    {
        return $this->data['expiresAt'] ?? 0;
    }

    /**
     * @return object|Mutex|null
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function getMutex()
    {
        $mutex = $this->data['mutex'] ?? null;
        return $mutex ? Instance::ensure($mutex) : null;
    }

    #endregion

    #region QueuedTaskInterface

    public function shouldQueued()
    {
        return $this->data['shouldQueued'] ?? false;
    }

    /**
     * @return object|\yii\queue\Queue
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function getQueue()
    {
        $queue = $this->data['queue'] ?? null;
        return $queue ? Instance::ensure($queue) : null;
    }

    public function getTtr()
    {
        return $this->data['ttr'] ?? 0;
    }

    public function getAttempts()
    {
        return $this->data['attempts'] ?? 0;
    }

    #endregion


    public function fields()
    {
        return [
            'taskCode',
            'taskDescription',
            'expression',
            'timezone',
            'isWithoutOverlapping',
            'mutex',
            'shouldQueued',
            'queue',
            'ttr',
            'attempts',
        ];
    }

    public function extraFields()
    {
        return [];
    }

    public function toArray(array $fields = [], array $expand = [], $recursive = true)
    {
        $toArray = [];
        $fields = $fields ?: $this->fields();
        foreach ($fields as $field) {
            $toArray[$field] = $this->data[$field] ?? null;
        }
        return $toArray;
    }
}
