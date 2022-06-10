<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\executing;

use lujie\extend\helpers\ClassHelper;
use Yii;

/**
 * Trait ExecUidTrait
 * @package lujie\executing
 */
trait ExecutableTrait
{
    /**
     * @var string|int
     */
    public $id;

    /**
     * @var string
     */
    public $execUid;

    /**
     * @var ?callable
     */
    public $executable;

    /**
     * @var string[]
     */
    public $executeMethods = ['handle', 'run'];

    /**
     * @return int|string
     * @inheritdoc
     */
    public function getId()
    {
        return $this->id ?: ClassHelper::getClassShortName(static::class);
    }

    /**
     * @return string
     * @throws \yii\base\Exception
     * @inheritdoc
     */
    public function getExecUid(): string
    {
        if (!$this->execUid) {
            $this->execUid = Yii::$app->security->generateRandomString();
        }
        return $this->execUid;
    }

    /**
     * @return string
     * @inheritdoc
     */
    public function getMemoryLimit(): ?string
    {
        return null;
    }

    /**
     * @return mixed|null
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function execute()
    {
        $executable = $this->executable;
        if ($executable) {
            if ($executable instanceof ExecutableInterface) {
                return $executable->execute();
            }
            if (is_array($executable) && isset($executable['class'])) {
                $callbackObject = Yii::createObject($executable);
                if ($callbackObject instanceof ExecutableInterface) {
                    return $callbackObject->execute();
                }
            }
            if (is_callable($executable)) {
                return $executable();
            }
        }

        foreach ($this->executeMethods as $method) {
            if (method_exists($this, $method)) {
                return $this->{$method}();
            }
        }
        return null;
    }
}
