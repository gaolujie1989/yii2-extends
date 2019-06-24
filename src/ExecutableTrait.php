<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\executing;

use Yii;
use yii\base\NotSupportedException;

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
     * @return int|string
     * @inheritdoc
     */
    public function getId()
    {
        return $this->id;
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
     * @return mixed
     * @throws NotSupportedException
     * @inheritdoc
     */
    public function execute()
    {
        $aliasMethods = ['handle', 'run'];
        foreach ($aliasMethods as $method) {
            if (method_exists($this, $method)) {
                return $this->{$method}();
            }
        }
        throw new NotSupportedException('Object not implement the execute method');
    }
}
