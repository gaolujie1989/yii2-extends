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
use yii\helpers\VarDumper;
use yii\mutex\Mutex;
use yii\queue\Queue;

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

    /**
     * @param string $name
     * @param mixed $value
     * @inheritdoc
     */
    public function __set($name, $value)
    {
        $setter = 'set' . $name;
        if (method_exists($this, $setter)) {
            $this->$setter($value);
        } else {
            $this->data[$name] = $value;
        }
    }

    #region TaskInterface

    /**
     * @return string
     * @inheritdoc
     */
    public function getTaskCode(): string
    {
        return $this->data['taskCode'];
    }

    /**
     * @return string
     * @inheritdoc
     */
    public function getTaskDescription(): string
    {
        return $this->data['taskDescription'] ?? '';
    }

    /**
     * @return bool
     * @inheritdoc
     */
    public function isDue(): bool
    {
        $date = Carbon::now();
        if ($this->getTimezone()) {
            $date->setTimezone($this->getTimezone());
        }
        return CronExpression::factory($this->getExpression())->isDue($date->toDateTimeString());;
    }

    /**
     * @return \DateTime
     * @throws \Exception
     * @inheritdoc
     */
    public function getNextRunTime(): \DateTime
    {
        $dateTime = new \DateTime();
        if ($this->getTimezone()) {
            $dateTime->setTimezone(new \DateTimeZone($this->getTimezone()));
        }
        return CronExpression::factory($this->getExpression())->getNextRunDate($dateTime);
    }

    /**
     * @return string
     * @inheritdoc
     */
    public function getExpression(): string
    {
        return $this->data['expression'];
    }

    /**
     * @return string
     * @inheritdoc
     */
    public function getTimezone(): string
    {
        return $this->data['timezone'] ?? Yii::$app->timeZone;
    }

    /**
     * @return bool|mixed
     * @throws InvalidConfigException
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
            $methods = ['execute', 'handle', 'run'];
            foreach ($methods as $method) {
                if (method_exists($object, $method)) {
                    return $object->{$method}();
                }
            }
            throw new InvalidConfigException('Invalid callback method');
        }
        if (is_callable($callback)) {
            return $callback();
        }
        return false;
    }

    #endregion

    #region WithoutOverlappingTaskInterface

    /**
     * @return bool
     * @inheritdoc
     */
    public function isWithoutOverlapping(): bool
    {
        return $this->data['isWithoutOverlapping'] ?? false;
    }

    /**
     * @return int
     * @inheritdoc
     */
    public function getExpiresAt(): int
    {
        return $this->data['expiresAt'] ?? 0;
    }

    /**
     * @return Mutex|null|object
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function getMutex(): ?Mutex
    {
        $mutex = $this->data['mutex'] ?? null;
        return $mutex ? Instance::ensure($mutex, Mutex::class) : null;
    }

    #endregion

    #region QueuedTaskInterface

    /**
     * @return bool
     * @inheritdoc
     */
    public function shouldQueued(): bool
    {
        return $this->data['shouldQueued'] ?? false;
    }

    /**
     * @return Queue|null|object
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function getQueue(): ?Queue
    {
        $queue = $this->data['queue'] ?? null;
        return $queue ? Instance::ensure($queue, Queue::class) : null;
    }

    /**
     * @return int
     * @inheritdoc
     */
    public function getTtr(): int
    {
        return $this->data['ttr'] ?? 0;
    }

    /**
     * @return int
     * @inheritdoc
     */
    public function getAttempts(): int
    {
        return $this->data['attempts'] ?? 0;
    }

    #endregion

    /**
     * @return array
     * @inheritdoc
     */
    public function fields(): array
    {
        return [
            'taskCode',
            'taskDescription',
            'expression',
            'callback',
            'timezone',
            'isWithoutOverlapping',
            'mutex',
            'shouldQueued',
            'queue',
            'ttr',
            'attempts',
        ];
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function extraFields(): array
    {
        return [];
    }

    /**
     * @param array $fields
     * @param array $expand
     * @param bool $recursive
     * @return array
     * @inheritdoc
     */
    public function toArray(array $fields = [], array $expand = [], $recursive = true): array
    {
        $toArray = [];
        $fields = $fields ?: $this->fields();
        foreach ($fields as $field) {
            $toArray[$field] = $this->data[$field] ?? null;
            if ($field === 'callback' && $toArray[$field]) {
                $toArray[$field] = VarDumper::export($toArray[$field]);
            }
        }
        return $toArray;
    }
}
