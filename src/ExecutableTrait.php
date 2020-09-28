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
     * @return bool
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function execute(): bool
    {
        $executable = $this->executable;
        if ($executable) {
            if (is_object($executable) && $executable instanceof ExecutableInterface) {
                $executable->execute();
                return true;
            }
            if (is_array($executable) && isset($executable['class'])) {
                $callbackObject = Yii::createObject($executable);
                if ($callbackObject instanceof ExecutableInterface) {
                    $callbackObject->execute();
                    return false;
                }
            }
            if (is_callable($executable)) {
                $executable();
                return true;
            }
        }
        $aliasMethods = ['handle', 'run'];
        foreach ($aliasMethods as $method) {
            if (method_exists($this, $method)) {
                $this->{$method}();
                return true;
            }
        }
        return false;
    }
}
